<?php

namespace App\Http\Requests\Game;

use Illuminate\Foundation\Http\FormRequest;

class StoreGameRequest extends FormRequest
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
            'title' => 'required|unique:games,title|max:255',

            'categories' => 'nullable|array',
            'categories.*.title' => 'required|string|max:255',
            'categories.*.unit_id' => 'required|exists:units,id',

            'servers' => 'nullable|array',
            'servers.*.title' => 'required|string|max:255',

            'attributes' => 'nullable|array',
            'attributes.*.title' => 'required|string|max:255',
        ];
    }
}
