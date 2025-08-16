<?php

namespace Hetbo\Zero\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AttachCarrotRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'model_type' => 'required|string',
            'model_id' => 'required|integer',
            'carrot_id' => 'required|integer|exists:carrots,id',
            'role' => 'required|string|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'model_type.required' => 'The model type is required.',
            'model_id.required' => 'The model ID is required.',
            'carrot_id.required' => 'The carrot ID is required.',
            'carrot_id.exists' => 'The selected carrot does not exist.',
            'role.required' => 'The role is required.',
            'role.max' => 'The role may not be greater than 255 characters.',
        ];
    }
}