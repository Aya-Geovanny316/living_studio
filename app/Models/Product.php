<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'short_description',
        'description',
        'price_estimate',
        'sku',
        'images',
        'specs',
        'image_position',
        'featured',
        'is_active',
        'stock_status',
    ];

    protected $casts = [
        'specs' => 'array',
        'featured' => 'boolean',
        'is_active' => 'boolean',
        'price_estimate' => 'decimal:2',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function promotions()
    {
        return $this->hasMany(Promotion::class);
    }

    protected function images(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $this->normalizeImages($value),
            set: fn ($value) => ['images' => $this->encodeImages($value)]
        );
    }

    private function normalizeImages($value): array
    {
        if (is_array($value)) {
            $images = $value;
        } elseif (is_string($value)) {
            $decoded = json_decode($value, true);
            $images = is_array($decoded) ? $decoded : [];
        } else {
            $images = [];
        }

        return array_map(function ($image) {
            if (is_string($image)) {
                return $this->normalizeImageUrl($image);
            }
            if (! is_array($image)) {
                return $image;
            }
            if (isset($image['url'])) {
                $image['url'] = $this->normalizeImageUrl($image['url']);
            }
            return $image;
        }, $images);
    }

    private function encodeImages($value): ?string
    {
        if (is_null($value)) {
            return null;
        }
        if (is_string($value)) {
            return $value;
        }
        if (is_array($value)) {
            return json_encode($value);
        }

        return json_encode([]);
    }

    private function normalizeImageUrl(?string $url): ?string
    {
        if (! $url) {
            return $url;
        }

        if (Str::startsWith($url, ['/storage/', 'storage/'])) {
            return Str::startsWith($url, '/storage/') ? $url : '/' . $url;
        }

        if (Str::startsWith($url, ['http://', 'https://'])) {
            $path = parse_url($url, PHP_URL_PATH);
            if ($path && Str::contains($path, '/storage/')) {
                return $path;
            }
        }

        return $url;
    }
}
