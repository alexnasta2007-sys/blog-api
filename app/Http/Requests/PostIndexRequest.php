<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PostIndexRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'limit' => ['nullable', 'integer', 'min:1', 'max:100'],
            'offset' => ['nullable', 'integer', 'min:0'],
            'sort' => [
                'nullable',
                'in:date,date_desc,title,title_desc',
            ],
            'date_from' => ['nullable', 'date'],
            'date_to' => ['nullable', 'date'],
        ];
    }
}