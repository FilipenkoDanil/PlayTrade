<?php

namespace App\Http\Requests\Offer;

use Illuminate\Foundation\Http\FormRequest;

class StoreOfferRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'nullable|string|max:100',
            'amount' => 'required|numeric|min:1',
            'price' => 'required|numeric|min:1',
            'description' => 'string|nullable|max:255',
            'auto_message' => 'string|nullable|max:255',
            'is_active' => 'boolean',
            'category_id' => 'required|integer|exists:categories,id',
            'seller_id' => 'required|integer|exists:users,id',
            'deleted_at' => 'nullable|date',
            'created_at' => 'nullable|date',
            'updated_at' => 'nullable|date',
        ];
    }
}
