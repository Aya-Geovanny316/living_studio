<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Str;

class Promotion extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'subtitle',
        'image_path',
        'link',
        'product_id',
        'discount_percent',
        'type',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
        'discount_percent' => 'integer',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    protected function imagePath(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $this->normalizeImagePath($value),
            set: fn ($value) => $value
        );
    }

    private function normalizeImagePath(?string $value): ?string
    {
        if (! $value) {
            return $value;
        }

        if (Str::startsWith($value, ['/storage/', 'storage/'])) {
            return Str::startsWith($value, '/storage/') ? $value : '/' . $value;
        }

        if (Str::startsWith($value, ['http://', 'https://'])) {
            $path = parse_url($value, PHP_URL_PATH);
            if ($path && Str::contains($path, '/storage/')) {
                return $path;
            }
        }

        return $value;
    }
}
