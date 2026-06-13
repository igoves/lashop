<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class SaveCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Authorization is handled by the 'admin' middleware on the route group.
        return true;
    }

    public function rules(): array
    {
        $categoryId = $this->route('category')?->id;

        return [
            'parent_id' => ['nullable', 'integer', Rule::exists('shop_categories', 'id')],
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', 'regex:/^[a-z0-9-]+$/', Rule::unique('shop_categories', 'slug')->ignore($categoryId)],
            'logo' => ['nullable', 'image', 'max:5120'],
            'fulldesc' => ['nullable', 'string'],
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string', 'max:255'],
            'meta_keywords' => ['nullable', 'string', 'max:255'],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            $category = $this->route('category');
            $parentId = (int) $this->input('parent_id');

            if ($category && $parentId) {
                if (in_array($parentId, $category->descendantIds(), true)) {
                    $validator->errors()->add('parent_id', 'A category cannot be moved to one of its own descendants.');
                }
            }
        });
    }
}
