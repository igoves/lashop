<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSettingRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Authorization is handled by IsAdmin middleware on the admin route group.
        return true;
    }

    public function rules(): array
    {
        return [
            'value' => ['nullable', 'string', 'max:1000'],
        ];
    }
}
