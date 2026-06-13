<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SaveProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Authorization is handled by the 'admin' middleware on the route group.
        return true;
    }

    public function rules(): array
    {
        $productId = $this->route('product')?->id;

        return [
            'cat_id' => ['required', 'integer', Rule::exists('shop_categories', 'id')],
            'brand_id' => ['nullable', 'integer', Rule::exists('shop_brands', 'id')],
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', 'regex:/^[a-z0-9-]+$/', Rule::unique('shop_products', 'slug')->ignore($productId)],
            'fulldesc' => ['nullable', 'string'],
            'cost' => ['required', 'numeric', 'min:0'],
            'status' => ['required', 'integer', Rule::in([0, 1])],
            'photo' => ['nullable', 'file', 'image', 'mimes:jpeg,jpg,png,gif,webp', 'max:5120'],
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string', 'max:255'],
            'meta_keywords' => ['nullable', 'string', 'max:255'],
        ];
    }
}
