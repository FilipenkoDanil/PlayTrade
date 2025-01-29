<?php

namespace App\Http\Requests\Rating;

use App\Models\Deal;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreRatingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $deal = Deal::findOrFail($this->input('deal_id'));

        return $deal && $deal->buyer_id == Auth::id();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'deal_id' => 'required|exists:deals,id',
            'rating' => 'required|integer|between:1,5',
            'comment' => 'nullable|string|max:255',
        ];
    }
}
