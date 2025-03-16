<?php

namespace App\Http\Requests\Offer;

use App\Models\Category;
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
        $category = Category::findOrFail($this->input('category_id'));
        $isCurrency = $category->type === 2;

        return [
            'title' => $isCurrency ? 'nullable|string|max:100' : 'required|string|max:100',
            'amount' => 'required|numeric|min:1',
            'price' => 'required|numeric|min:1',
            'description' => 'string|nullable|max:255',
            'auto_message' => 'string|nullable|max:255',
            'server_id' => 'integer|exists:servers,id',
            'category_id' => 'required|integer|exists:categories,id',
            'attributes' => 'array',
            'attributes.*.id' => 'required|integer|exists:attributes,id',
            'attributes.*.value' => 'required|string|max:255',
        ];
    }
}
