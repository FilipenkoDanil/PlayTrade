<?php

namespace App\Http\Requests\Offer;

use App\Models\Category;
use Illuminate\Foundation\Http\FormRequest;

class UpdateOfferRequest extends FormRequest
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
        $offer = $this->route('offer');
        $category = $offer->category;
        $isCurrency = $category->type === 2;

        return [
            'title' => $isCurrency ? 'nullable|string|max:100' : 'required|string|max:100',
            'amount' => 'required|numeric|min:1',
            'price' => 'required|numeric|min:1',
            'description' => 'string|nullable|max:255',
            'auto_message' => 'string|nullable|max:255',
            'is_active' => 'boolean',
            'server_id' => 'nullable|integer|exists:servers,id',
            'attributes' => 'array',
            'attributes.*.id' => 'required|integer|exists:attributes,id',
            'attributes.*.value' => 'required|string|max:255',
        ];
    }
}
