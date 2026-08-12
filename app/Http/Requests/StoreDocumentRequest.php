<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDocumentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['nullable', 'string', 'max:150'],
            'category_id' => ['required', 'exists:categories,id'],
            'files' => ['required', 'array', 'min:1'],
            'files.*' => [
                'file',
                'max:20480', // 20 MB
                'mimes:pdf,doc,docx,jpg,jpeg,png,zip,xlsx,pptx',
            ],
        ];
    }
}
