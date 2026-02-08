<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ProductImportRequest;
use App\Http\Requests\Admin\ProductRequest;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Inertia\Inertia;
use OpenSpout\Common\Entity\Row;
use OpenSpout\Reader\XLSX\Reader as XlsxReader;
use OpenSpout\Writer\XLSX\Writer as XlsxWriter;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('category')->latest()->paginate(15);

        return Inertia::render('Admin/Products/Index', [
            'products' => $products,
        ]);
    }

    public function create()
    {
        $categories = Category::orderBy('sort_order')->get();

        return Inertia::render('Admin/Products/Create', [
            'categories' => $categories,
        ]);
    }

    public function importForm()
    {
        return Inertia::render('Admin/Products/Import');
    }

    public function importTemplate()
    {
        $headers = [
            'name',
            'category_id',
            'category_name',
            'price_estimate',
            'sku',
            'stock_status',
            'short_description',
            'description',
            'featured',
            'is_active',
            'image_position',
            'image_url',
            'specs',
        ];

        $example = [
            'Silla nórdica',
            '1',
            'Sillas',
            '899.00',
            'SILLA-001',
            'En stock',
            'Silla tapizada con patas en madera.',
            'Descripción larga del producto.',
            '1',
            '1',
            'center',
            'https://example.com/imagen.jpg',
            'Color:Gris|Material:Tela|Altura:85cm',
        ];

        $handle = fopen('php://temp', 'r+');
        fputcsv($handle, $headers);
        fputcsv($handle, $example);
        rewind($handle);
        $csv = stream_get_contents($handle);
        fclose($handle);

        return response($csv, 200, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="plantilla-productos.csv"',
        ]);
    }

    public function importTemplateXlsx()
    {
        $headers = [
            'Categoria',
            'SKU',
            'Nombre',
            'Descripcion',
            'Precio (Q)',
        ];

        $example = [
            'Sillas',
            'SILLA-001',
            'Silla nordica',
            'Silla tapizada con patas en madera.',
            '899.00',
        ];

        $tempPath = tempnam(sys_get_temp_dir(), 'plantilla-productos-');
        $filePath = $tempPath . '.xlsx';
        @rename($tempPath, $filePath);

        $writer = new XlsxWriter();
        $writer->openToFile($filePath);
        $writer->addRow(Row::fromValues($headers));
        $writer->addRow(Row::fromValues($example));
        $writer->close();

        return response()->download($filePath, 'plantilla-productos.xlsx')->deleteFileAfterSend(true);
    }

    public function importStoreXlsx(ProductImportRequest $request)
    {
        $path = $request->file('file')->getRealPath();
        $extension = strtolower((string) $request->file('file')->getClientOriginalExtension());

        try {
            $rows = $this->readRowsFromFile($path, $extension);
        } catch (\Throwable $e) {
            return back()->with('error', 'No se pudo leer el archivo.');
        }

        if (! count($rows)) {
            return back()->with('error', 'El archivo no tiene datos.');
        }

        $headerRow = array_shift($rows);
        if (! $headerRow) {
            return back()->with('error', 'El archivo no tiene encabezados.');
        }

        $header = [];
        foreach ($headerRow as $value) {
            $header[] = $this->normalizeHeaderKey((string) $value);
        }

        $requiredColumns = ['category_name', 'sku', 'name', 'price_estimate'];
        foreach ($requiredColumns as $column) {
            if (! in_array($column, $header, true)) {
                return back()->with('error', 'Falta la columna requerida: ' . $column);
            }
        }

        $rowIndex = 1;
        $created = 0;
        $updated = 0;
        $skipped = 0;
        $errors = [];

        foreach ($rows as $row) {
            $rowIndex++;
            if (! $this->rowHasValues($row)) {
                continue;
            }

            $record = [];
            foreach ($header as $index => $key) {
                $record[$key] = isset($row[$index]) ? trim((string) $row[$index]) : null;
            }

            $name = $record['name'] ?? '';
            if ($name === '') {
                $errors[] = "Fila {$rowIndex}: nombre vacio.";
                $skipped++;
                continue;
            }

            $sku = trim((string) ($record['sku'] ?? ''));
            if ($sku === '') {
                $errors[] = "Fila {$rowIndex}: SKU vacio.";
                $skipped++;
                continue;
            }

            $price = $this->parseMoney($record['price_estimate'] ?? '');
            if ($price === null) {
                $errors[] = "Fila {$rowIndex}: precio invalido.";
                $skipped++;
                continue;
            }

            $category = null;
            $categoryName = trim((string) ($record['category_name'] ?? ''));
            if (! $category && $categoryName !== '') {
                $category = Category::whereRaw('LOWER(name) = ?', [Str::lower($categoryName)])->first();
                if (! $category) {
                    $category = Category::create([
                        'name' => $categoryName,
                        'slug' => Str::slug($categoryName),
                        'is_active' => true,
                        'sort_order' => 0,
                    ]);
                }
            }
            if (! $category) {
                $errors[] = "Fila {$rowIndex}: categoria invalida.";
                $skipped++;
                continue;
            }

            $data = [
                'name' => $name,
                'category_id' => $category->id,
                'short_description' => null,
                'description' => $record['description'] ?? null,
                'price_estimate' => $price,
                'sku' => $sku,
                'stock_status' => null,
                'featured' => false,
                'is_active' => true,
                'image_position' => 'center',
                'specs' => null,
                'images' => [],
            ];

            $existing = Product::where('sku', $sku)->first();
            if ($existing) {
                $data['slug'] = $this->makeSlug($name, $existing->id);
                $existing->update($data);
                $updated++;
            } else {
                $data['slug'] = $this->makeSlug($name);
                Product::create($data);
                $created++;
            }
        }

        if ($created === 0 && count($errors)) {
            return back()->with('error', 'No se importaron productos. ' . $this->summarizeErrors($errors));
        }

        $message = "Productos importados: {$created}.";
        if ($updated > 0) {
            $message .= " Productos actualizados: {$updated}.";
        }
        if ($skipped > 0) {
            $message .= " Filas omitidas: {$skipped}.";
        }
        if (count($errors)) {
            $message .= ' ' . $this->summarizeErrors($errors);
        }

        return back()->with('success', $message);
    }

    public function importStore(ProductImportRequest $request)
    {
        $path = $request->file('file')->getRealPath();
        $handle = fopen($path, 'r');
        if (! $handle) {
            return back()->with('error', 'No se pudo leer el archivo.');
        }

        $header = fgetcsv($handle);
        if (! $header) {
            fclose($handle);
            return back()->with('error', 'El archivo no tiene encabezados.');
        }

        $header = array_map(fn ($value) => Str::slug(trim((string) $value), '_'), $header);
        $requiredColumns = ['name', 'price_estimate'];
        foreach ($requiredColumns as $column) {
            if (! in_array($column, $header, true)) {
                fclose($handle);
                return back()->with('error', 'Falta la columna requerida: ' . $column);
            }
        }

        $rowIndex = 1;
        $created = 0;
        $skipped = 0;
        $errors = [];

        while (($row = fgetcsv($handle)) !== false) {
            $rowIndex++;
            if (count($row) === 1 && trim((string) $row[0]) === '') {
                continue;
            }

            $record = [];
            foreach ($header as $index => $key) {
                $record[$key] = isset($row[$index]) ? trim((string) $row[$index]) : null;
            }

            $name = $record['name'] ?? '';
            if ($name === '') {
                $errors[] = "Fila {$rowIndex}: nombre vacío.";
                $skipped++;
                continue;
            }

            $price = $this->parseMoney($record['price_estimate'] ?? '');
            if ($price === null) {
                $errors[] = "Fila {$rowIndex}: precio inválido.";
                $skipped++;
                continue;
            }

            $categoryId = (int) ($record['category_id'] ?? 0);
            $category = null;
            if ($categoryId > 0) {
                $category = Category::find($categoryId);
            }
            if (! $category && ! empty($record['category_name'])) {
                $category = Category::whereRaw('LOWER(name) = ?', [Str::lower($record['category_name'])])->first();
            }
            if (! $category) {
                $errors[] = "Fila {$rowIndex}: categoría inválida.";
                $skipped++;
                continue;
            }

            $imagePosition = $this->normalizeImagePosition($record['image_position'] ?? '');
            $images = $this->downloadImagesFromUrls($record['image_url'] ?? '', $imagePosition, $rowIndex, $errors);

            $data = [
                'name' => $name,
                'category_id' => $category->id,
                'slug' => $this->makeSlug($name),
                'short_description' => $record['short_description'] ?? null,
                'description' => $record['description'] ?? null,
                'price_estimate' => $price,
                'sku' => $record['sku'] ?? null,
                'stock_status' => $record['stock_status'] ?? null,
                'featured' => $this->parseBoolean($record['featured'] ?? null),
                'is_active' => $this->parseBoolean($record['is_active'] ?? null, true),
                'image_position' => $imagePosition,
                'specs' => $this->parseSpecsString($record['specs'] ?? null),
                'images' => $images,
            ];

            Product::create($data);
            $created++;
        }

        fclose($handle);

        if ($created === 0 && count($errors)) {
            return back()->with('error', 'No se importaron productos. ' . $this->summarizeErrors($errors));
        }

        $message = "Productos importados: {$created}.";
        if ($skipped > 0) {
            $message .= " Filas omitidas: {$skipped}.";
        }
        if (count($errors)) {
            $message .= ' ' . $this->summarizeErrors($errors);
        }

        return back()->with('success', $message);
    }

    public function bulkDestroy(Request $request)
    {
        $data = $request->validate([
            'ids' => ['required', 'array'],
            'ids.*' => ['integer', 'exists:products,id'],
        ]);

        $products = Product::whereIn('id', $data['ids'])->get();
        foreach ($products as $product) {
            $this->deleteImages($this->extractImagePaths($product->images ?? []));
        }
        $count = Product::whereIn('id', $data['ids'])->delete();

        return back()->with('success', "Productos eliminados: {$count}.");
    }

    public function store(ProductRequest $request)
    {
        $data = $request->validated();
        $data['slug'] = $this->makeSlug($data['name']);
        $data['featured'] = $request->boolean('featured');
        $data['is_active'] = $request->boolean('is_active', true);
        $data['specs'] = $this->parseSpecs($request->input('specs', []));
        $data['image_position'] = $request->input('image_position', 'center');
        $existingImages = $this->normalizeExistingImages($request->input('existing_images', []));
        $uploads = $this->storeImages($request, $data['image_position']);
        $data['images'] = array_merge($existingImages, $uploads);

        Product::create($data);

        return redirect()->route('admin.products.index')->with('success', 'Producto creado.');
    }

    public function edit(Product $product)
    {
        $categories = Category::orderBy('sort_order')->get();

        return Inertia::render('Admin/Products/Edit', [
            'product' => $product,
            'categories' => $categories,
        ]);
    }

    public function update(ProductRequest $request, Product $product)
    {
        $data = $request->validated();
        $data['slug'] = $this->makeSlug($data['name'], $product->id);
        $data['featured'] = $request->boolean('featured');
        $data['is_active'] = $request->boolean('is_active', true);
        $data['specs'] = $this->parseSpecs($request->input('specs', []));
        $data['image_position'] = $request->input('image_position', 'center');

        $previousPaths = $this->extractImagePaths($product->images ?? []);
        $existingImages = $this->normalizeExistingImages($request->input('existing_images', []));
        $uploads = $this->storeImages($request, $data['image_position']);
        if ($request->boolean('images_touched') || $request->hasFile('images') || $request->has('existing_images')) {
            $data['images'] = array_merge($existingImages, $uploads);
        }

        $product->update($data);
        if ($request->boolean('images_touched') || $request->hasFile('images') || $request->has('existing_images')) {
            $currentPaths = $this->extractImagePaths($data['images'] ?? []);
            $removed = array_diff($previousPaths, $currentPaths);
            $this->deleteImages($removed);
        }

        return redirect()->route('admin.products.index')->with('success', 'Producto actualizado.');
    }

    public function destroy(Product $product)
    {
        $this->deleteImages($this->extractImagePaths($product->images ?? []));
        $product->delete();

        return redirect()->route('admin.products.index')->with('success', 'Producto eliminado.');
    }

    private function storeImages(ProductRequest $request, string $defaultPosition): array
    {
        $paths = [];
        if ($request->hasFile('images')) {
            $meta = $request->input('new_images_meta', []);
            foreach ($request->file('images') as $image) {
                if (! $image) {
                    continue;
                }
                $index = count($paths);
                $position = $meta[$index]['position'] ?? $defaultPosition;
                $stored = $image->store('products', 'public');
                $paths[] = [
                    'url' => '/storage/' . ltrim($stored, '/'),
                    'position' => $position,
                ];
            }
        }

        return $paths;
    }

    private function normalizeExistingImages(array $images): array
    {
        $normalized = [];
        foreach ($images as $image) {
            $url = trim((string) ($image['url'] ?? ''));
            if ($url === '') {
                continue;
            }
            $normalized[] = [
                'url' => $url,
                'position' => $image['position'] ?? 'center',
            ];
        }

        return $normalized;
    }

    private function extractImagePaths(array $images): array
    {
        $paths = [];
        foreach ($images as $image) {
            $url = is_array($image) ? ($image['url'] ?? null) : $image;
            $path = $this->extractPublicPath($url);
            if ($path) {
                $paths[] = $path;
            }
        }

        return array_values(array_unique($paths));
    }

    private function extractPublicPath(?string $url): ?string
    {
        if (! $url) {
            return null;
        }

        $path = $url;
        if (Str::startsWith($url, ['http://', 'https://'])) {
            $path = parse_url($url, PHP_URL_PATH) ?: '';
        }

        if (Str::startsWith($path, '/storage/')) {
            return ltrim(Str::after($path, '/storage/'), '/');
        }
        if (Str::startsWith($path, 'storage/')) {
            return ltrim(Str::after($path, 'storage/'), '/');
        }
        if (Str::startsWith($path, 'products/')) {
            return ltrim($path, '/');
        }

        return null;
    }

    private function deleteImages(array $paths): void
    {
        foreach ($paths as $path) {
            Storage::disk('public')->delete($path);
        }
    }

    private function parseSpecs(array $specs): ?array
    {
        $clean = [];
        foreach ($specs as $item) {
            $key = trim((string) ($item['key'] ?? ''));
            $value = trim((string) ($item['value'] ?? ''));
            if ($key !== '' && $value !== '') {
                $clean[$key] = $value;
            }
        }

        return count($clean) ? $clean : null;
    }

    private function parseSpecsString(?string $specs): ?array
    {
        if (! $specs) {
            return null;
        }

        $clean = [];
        foreach (explode('|', $specs) as $pair) {
            $parts = explode(':', $pair, 2);
            if (count($parts) !== 2) {
                continue;
            }
            $key = trim($parts[0]);
            $value = trim($parts[1]);
            if ($key !== '' && $value !== '') {
                $clean[$key] = $value;
            }
        }

        return count($clean) ? $clean : null;
    }

    private function readRowsFromFile(string $path, string $extension): array
    {
        if ($extension === 'xlsx') {
            $reader = new XlsxReader();
            $reader->open($path);
            $rows = [];
            foreach ($reader->getSheetIterator() as $sheet) {
                foreach ($sheet->getRowIterator() as $row) {
                    $rows[] = $row->toArray();
                }
                break;
            }
            $reader->close();

            return $rows;
        }

        $handle = fopen($path, 'r');
        if (! $handle) {
            throw new \RuntimeException('No se pudo abrir el archivo.');
        }

        $rows = [];
        while (($row = fgetcsv($handle)) !== false) {
            $rows[] = $row;
        }
        fclose($handle);

        return $rows;
    }

    private function rowHasValues(array $row): bool
    {
        foreach ($row as $cell) {
            if (trim((string) $cell) !== '') {
                return true;
            }
        }

        return false;
    }

    private function normalizeHeaderKey(string $value): string
    {
        $key = Str::slug($value, '_');
        if (Str::startsWith($key, 'especificaciones')) {
            return 'specs';
        }

        $map = [
            'nombre' => 'name',
            'name' => 'name',
            'categoria_id' => 'category_id',
            'category_id' => 'category_id',
            'categoria_nombre' => 'category_name',
            'categoria' => 'category_name',
            'category_name' => 'category_name',
            'precio' => 'price_estimate',
            'precio_q' => 'price_estimate',
            'price_estimate' => 'price_estimate',
            'sku' => 'sku',
            'estado_stock' => 'stock_status',
            'stock_status' => 'stock_status',
            'descripcion_corta' => 'short_description',
            'short_description' => 'short_description',
            'descripcion' => 'description',
            'description' => 'description',
            'destacado' => 'featured',
            'destacado_si_no' => 'featured',
            'featured' => 'featured',
            'activo' => 'is_active',
            'activo_si_no' => 'is_active',
            'is_active' => 'is_active',
            'posicion_imagen' => 'image_position',
            'image_position' => 'image_position',
            'url_imagen' => 'image_url',
            'imagen_url' => 'image_url',
            'image_url' => 'image_url',
            'specs' => 'specs',
        ];

        return $map[$key] ?? $key;
    }

    private function parseBoolean($value, bool $default = false): bool
    {
        if ($value === null || $value === '') {
            return $default;
        }

        $normalized = Str::lower(trim((string) $value));
        $truthy = ['1', 'true', 'si', 'sí', 'yes', 'y'];
        $falsy = ['0', 'false', 'no', 'n'];

        if (in_array($normalized, $truthy, true)) {
            return true;
        }
        if (in_array($normalized, $falsy, true)) {
            return false;
        }

        return $default;
    }

    private function parseMoney(string $value): ?float
    {
        $clean = trim($value);
        if ($clean === '') {
            return null;
        }
        $clean = str_replace(['Q', 'q', ' '], '', $clean);
        $clean = str_replace(',', '.', $clean);

        return is_numeric($clean) ? (float) $clean : null;
    }

    private function normalizeImagePosition(?string $value): string
    {
        $value = Str::lower(trim((string) $value));
        if (in_array($value, ['top', 'center', 'bottom'], true)) {
            return $value;
        }

        return 'center';
    }

    private function downloadImagesFromUrls(?string $value, string $position, int $rowIndex, array &$errors): array
    {
        $value = trim((string) $value);
        if ($value === '') {
            return [];
        }

        $images = [];
        $urls = array_filter(array_map('trim', explode('|', $value)));
        foreach ($urls as $url) {
            $image = $this->downloadImage($url, $position);
            if (! $image) {
                $errors[] = "Fila {$rowIndex}: no se pudo descargar la imagen {$url}.";
                continue;
            }
            $images[] = $image;
        }

        return $images;
    }

    private function downloadImage(string $url, string $position): ?array
    {
        if (! Str::startsWith($url, ['http://', 'https://'])) {
            return null;
        }

        $response = Http::timeout(15)->get($url);
        if (! $response->successful()) {
            return null;
        }

        $contentType = $response->header('Content-Type', '');
        if (! Str::startsWith(Str::lower($contentType), 'image/')) {
            return null;
        }

        $extension = $this->guessImageExtension($url, $contentType) ?? 'jpg';
        $filename = 'products/' . Str::uuid() . '.' . $extension;
        Storage::disk('public')->put($filename, $response->body());

        return [
            'url' => '/storage/' . ltrim($filename, '/'),
            'position' => $position,
        ];
    }

    private function guessImageExtension(string $url, string $contentType): ?string
    {
        $map = [
            'image/jpeg' => 'jpg',
            'image/jpg' => 'jpg',
            'image/png' => 'png',
            'image/webp' => 'webp',
            'image/gif' => 'gif',
        ];

        $contentType = Str::lower($contentType);
        if (isset($map[$contentType])) {
            return $map[$contentType];
        }

        $path = parse_url($url, PHP_URL_PATH);
        if ($path) {
            $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
            if ($ext !== '') {
                return $ext;
            }
        }

        return null;
    }

    private function summarizeErrors(array $errors): string
    {
        $total = count($errors);
        $preview = array_slice($errors, 0, 5);
        $message = 'Errores: ' . implode(' ', $preview);
        if ($total > 5) {
            $message .= " (+{$total} en total).";
        }

        return $message;
    }

    private function makeSlug(string $name, ?int $ignoreId = null): string
    {
        $base = Str::slug($name);
        $slug = $base;
        $suffix = 1;

        while (
            Product::where('slug', $slug)
                ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
                ->exists()
        ) {
            $slug = $base . '-' . $suffix;
            $suffix++;
        }

        return $slug;
    }
}
