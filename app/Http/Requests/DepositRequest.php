<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DepositRequest extends FormRequest
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
            'amount' => ['required', 'integer'],
            'reference_number' => ['required', 'integer', 'digits:6'],
            'agent_bank_id' => ['required', 'exists:banks,id'],
        ];
    }
}
