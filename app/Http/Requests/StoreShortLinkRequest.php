<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreShortLinkRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'original_url' => ['required','url'],
            'custom_code' => ['nullable','string','max:10','regex:/^[A-Za-z0-9_-]+$/','unique:short_links,code'],
        ];
    }

    public function messages(): array
    {
        return [
            'original_url.required' => 'The original_url field is required.',
            'original_url.url' => 'The original_url must be a valid URL.',
            'custom_code.max' => 'The custom_code must not be greater than 10 characters.',
            'custom_code.regex' => 'The custom_code may only contain letters, numbers, underscores and dashes.',
            'custom_code.unique' => 'The custom_code has already been taken.',
        ];
    }
}
