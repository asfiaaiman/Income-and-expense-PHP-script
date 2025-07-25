<?php

namespace App\Http\Requests;

use App\Enums\TransactionType;
use Illuminate\Foundation\Http\FormRequest;

class TransactionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'type' => ['required', 'string', 'in:' . implode(',', TransactionType::toArray())],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'commission' => ['required', 'numeric', 'min:0'],
            'category_id' => ['required', 'integer', 'exists:categories,id'],
            'date' => ['required', 'date'],
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'date' => $this->date ?: now()->format('Y-m-d'),
        ]);
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Transaction title is required.',
            'title.string' => 'Transaction title must be a valid text.',
            'title.max' => 'Transaction title cannot exceed 255 characters.',
            'type.required' => 'Transaction type is required.',
            'type.in' => 'Transaction type must be either income or expense.',
            'amount.required' => 'Transaction amount is required.',
            'amount.numeric' => 'Transaction amount must be a valid number.',
            'amount.min' => 'Transaction amount must be at least 0.01.',
            'commission.required' => 'Transaction commission is required.',
            'commission.numeric' => 'Transaction commission must be a valid number.',
            'commission.min' => 'Transaction commission cannot be negative.',
            'category_id.required' => 'Please select a category.',
            'category_id.integer' => 'Category must be a valid selection.',
            'category_id.exists' => 'Selected category does not exist.',
            'date.required' => 'Transaction date is required.',
            'date.date' => 'Transaction date must be a valid date.',
        ];
    }
}
