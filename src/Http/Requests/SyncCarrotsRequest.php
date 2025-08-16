<?php

namespace Hetbo\Zero\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SyncCarrotsRequest extends FormRequest
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
            'carrot_ids' => 'required|array',
            'carrot_ids.*' => 'integer|exists:carrots,id',
            'role' => 'required|string|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'model_type.required' => 'The model type is required.',
            'model_id.required' => 'The model ID is required.',
            'carrot_ids.required' => 'The carrot IDs are required.',
            'carrot_ids.array' => 'The carrot IDs must be an array.',
            'carrot_ids.*.integer' => 'Each carrot ID must be an integer.',
            'carrot_ids.*.exists' => 'One or more selected carrots do not exist.',
            'role.required' => 'The role is required.',
            'role.max' => 'The role may not be greater than 255 characters.',
        ];
    }
}