<?php

namespace Hetbo\Zero\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateCarrotRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'length' => 'required|integer|min:1',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'The carrot name is required.',
            'name.string' => 'The carrot name must be a string.',
            'name.max' => 'The carrot name may not be greater than 255 characters.',
            'length.required' => 'The carrot length is required.',
            'length.integer' => 'The carrot length must be an integer.',
            'length.min' => 'The carrot length must be at least 1.',
        ];
    }
}