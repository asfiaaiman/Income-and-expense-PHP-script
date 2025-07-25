<?php

namespace App\Http\Requests;

use App\Enums\CategoryType;
use Illuminate\Foundation\Http\FormRequest;

class CategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'type' => ['required', 'string', 'in:' . implode(',', CategoryType::toArray())],
            'parent_id' => ['nullable', 'integer', 'exists:categories,id'],
            'is_active' => ['boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Category name is required.',
            'title.string' => 'Category name must be a valid text.',
            'title.max' => 'Category name cannot exceed 255 characters.',
            'type.required' => 'Category type is required.',
            'type.in' => 'Category type must be either income or expense.',
            'parent_id.integer' => 'Parent category must be a valid selection.',
            'parent_id.exists' => 'Selected parent category does not exist.',
        ];
    }
}
