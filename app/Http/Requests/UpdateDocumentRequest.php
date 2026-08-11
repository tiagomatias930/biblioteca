<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDocumentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:150'],
            'category_id' => ['required', 'exists:categories,id'],
            'file' => [
                'nullable',
                'file',
                'max:20480', // 20 MB
                'mimes:pdf,doc,docx,jpg,jpeg,png,zip,xlsx,pptx',
            ],
        ];
    }
}
