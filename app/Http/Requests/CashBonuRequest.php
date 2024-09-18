<?php

namespace App\Http\Requests;

use App\Models\User;
use App\Services\CashBonuWebhookValidator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;

class CashBonuRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Set to true if authorization logic is handled elsewhere
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            // Define validation rules if needed
        ];
    }

    public function check()
    {
        $validator = CashBonuWebhookValidator::make($this)->validate();

        return $validator;
    }

    public function getMember()
    {
        $playerId = $this->getMemberName();

        return User::where('user_name', $playerId)->first();
    }

    public function getMemberName()
    {
        return $this->get('PlayerId');
    }

    public function getTranId()
    {
        return $this->get('TranId');
    }

    public function getBonusId()
    {
        return $this->get('BonusId');
    }

    public function getBonusName()
    {
        return $this->get('BonusName');
    }

    public function getResult()
    {
        return $this->get('Result');
    }

    public function getCurrency()
    {
        return $this->get('Currency');
    }

    public function getMethodName()
    {
        return str($this->url())->explode('/')->last();
    }

    public function getExchangeRate()
    {
        return $this->get('ExchangeRate');
    }

    public function getPayout()
    {
        return $this->get('Payout');
    }

    public function getPlayerId()
    {
        return $this->get('PlayerId');
    }

    public function getTransactions()
    {
        $transactions = $this->get('Transactions', [$this->all()]);

        // Assuming Status and ProductID are part of the request, add them to the transactions
        foreach ($transactions as &$transaction) {
            $transaction['Status'] = $this->get('Status', 1); // Defaulting to 1 if not provided
            $transaction['ProductID'] = $this->get('ProductID', 'default_product_id'); // Default value if not provided
        }

        // Log the transactions for debugging
        Log::info('Retrieved Transactions', [
            'transactions' => $transactions,
        ]);

        return $transactions;
    }

    public function getTranDateTime()
    {
        return $this->get('TranDateTime');
    }

    public function getProviderTimeZone()
    {
        return $this->get('ProviderTimeZone');
    }

    public function getProviderTranDt()
    {
        return $this->get('ProviderTranDt');
    }

    public function getOperatorId()
    {
        return $this->get('OperatorId');
    }

    public function getRequestDateTime()
    {
        return $this->get('RequestDateTime');
    }

    public function getOperatorCode()
    {
        return $this->get('OperatorId');
    }

    public function getRequestTime()
    {
        return $this->get('RequestDateTime');
    }

    public function getSignature()
    {
        return $this->get('Signature');
    }
}
