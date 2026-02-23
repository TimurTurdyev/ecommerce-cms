<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'parent_id' => [
                'nullable',
                'integer',
                Rule::when(
                    $this->parent_id != 0, // The condition: if the value is not 0
                    [Rule::exists('categories', 'id')] // Rules to apply if true
                ),
            ],
            'image' => ['nullable', 'string', 'max:255'],
            'sort_order' => ['nullable', 'integer'],
            'status' => ['nullable', 'boolean'],
            'description' => ['nullable', 'string'],
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_h1' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string'],
            'meta_keyword' => ['nullable', 'string'],
        ];
    }

    protected function prepareForValidation(): void
    {
        if (!$this->input('parent_id')) {
            $this->merge(['parent_id' => 0]);
        }

        if (!$this->has('status')) {
            $this->merge(['status' => false]);
        }
    }
}
