<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $categoryId = $this->route('category')?->id;

        return [
            'name' => ['required', 'string', 'max:120'],
            'description' => ['nullable', 'string', 'max:1000'],
            'cover_image' => ['nullable', 'image', 'max:4096'],
            'is_protected' => ['sometimes', 'boolean'],
            'access_code' => [
                Rule::requiredIf(fn () => $this->boolean('is_protected') && ! $categoryId),
                'nullable', 'string', 'min:4', 'max:64',
            ],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ];
    }

    public function messages(): array
    {
        return [
            'access_code.required' => 'Defina um código de acesso para categorias protegidas.',
        ];
    }
}
