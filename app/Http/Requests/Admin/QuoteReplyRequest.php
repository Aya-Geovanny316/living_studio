<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class QuoteReplyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'response_message' => ['required', 'string', 'max:2000'],
            'response_total_estimate' => ['nullable', 'numeric', 'min:0'],
        ];
    }
}
