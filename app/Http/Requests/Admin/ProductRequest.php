<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:150'],
            'category_id' => ['required', 'exists:categories,id'],
            'short_description' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'price_estimate' => ['required', 'numeric', 'min:0'],
            'sku' => ['nullable', 'string', 'max:60'],
            'stock_status' => ['nullable', 'string', 'max:50'],
            'featured' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
            'specs' => ['nullable', 'array'],
            'specs.*.key' => ['nullable', 'string', 'max:80'],
            'specs.*.value' => ['nullable', 'string', 'max:500'],
            'image_position' => ['nullable', 'in:top,center,bottom'],
            'existing_images' => ['nullable', 'array'],
            'existing_images.*.url' => ['required_with:existing_images', 'string', 'max:255'],
            'existing_images.*.position' => ['nullable', 'in:top,center,bottom'],
            'new_images_meta' => ['nullable', 'array'],
            'new_images_meta.*.position' => ['nullable', 'in:top,center,bottom'],
            'images_touched' => ['nullable', 'boolean'],
            'images.*' => ['nullable', 'image', 'max:4096'],
        ];
    }
}
