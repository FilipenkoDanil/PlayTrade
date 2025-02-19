<?php

namespace App\Http\Requests\Category;

use App\Models\Attribute;
use App\Models\Server;
use Illuminate\Foundation\Http\FormRequest;

class StoreCategoryRequest extends FormRequest
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
            'title' => 'required|max:255',
            'description' => 'nullable',
            'game_id' => 'required|exists:games,id',
            'unit_id' => 'required|exists:units,id',
            'attributes' => 'array',
            'attributes.*' => [
                'exists:attributes,id',
                function ($attribute, $value, $fail) {
                    $gameId = request('game_id');
                    if (!Attribute::where('id', $value)->where('game_id', $gameId)->exists()) {
                        $fail("Attribute ID $value does not belong to the specified game.");
                    }
                }
            ],
            'servers' => 'array',
            'servers.*' => [
                'exists:servers,id',
                function ($attribute, $value, $fail) {
                    $gameId = request('game_id');
                    if (!Server::where('id', $value)->where('game_id', $gameId)->exists()) {
                        $fail("Server ID $value does not belong to the specified game.");
                    }
                }
            ],
        ];
    }
}
