<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GameLoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'game_code' => 'required|string',
            'launch_demo' => 'sometimes|boolean',
        ];
    }
}
