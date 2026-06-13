<?php

namespace App\Http\Requests;

use App\Models\Shop\Product;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AddToCartRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'product_id' => [
                'required', 'integer',
                Rule::exists('shop_products', 'id')
                    ->where('status', Product::STATUS_ACTIVE),
            ],
            'qty' => ['nullable', 'integer', 'min:1', 'max:999'],
        ];
    }
}
