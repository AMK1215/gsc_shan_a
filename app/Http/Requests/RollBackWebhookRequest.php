<?php

namespace App\Http\Requests;

use App\Models\Admin\GameType;
use App\Models\GameList;
use App\Models\User;
use App\Services\RollBackWebhookValidator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;

class RollBackWebhookRequest extends FormRequest
{
    private ?User $member;

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
        ];
    }

    public function check()
    {
        $validator = RollBackWebhookValidator::make($this)->validate();

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

    public function getRollbackType()
    {
        return $this->get('RollbackType');
    }

    public function getGameListID()
    {
        $game_code = $this->GetGameCode();

        return GameList::where('game_code', $game_code)->first();

    }

    public function GetGameCode()
    {
        return $this->get('GameCode');
    }

    public function GetBetID()
    {
        return $this->get('BetId');
    }

    public function ResultID()
    {
        return $this->get('ResultId');
    }

    public function GetResultType()
    {
        return $this->get('ResultType');
    }

    public function getGameTypeID()
    {
        $game_type = GameType::where('name', $this->GetGameType())->first();

        return $game_type->id;
    }

    public function GetGameType()
    {
        return $this->get('GameType');
    }

    public function getBetAmount()
    {
        return $this->get('BetAmount');
    }

    public function getValidBetAmount()
    {
        return $this->get('ValidBetAmount');
    }

    public function getPayout()
    {
        return $this->get('Payout');
    }

    public function getWinLose()
    {
        return $this->get('WinLose');
    }

    public function getMethodName()
    {
        return str($this->url())->explode('/')->last();
    }

    public function getExchangeRate()
    {
        return $this->get('ExchangeRate');
    }

    public function getOperatorCode()
    {
        return $this->get('OperatorId');
    }

    public function getRequestTime()
    {
        return $this->get('RequestDateTime');
    }

    public function getSign()
    {
        return $this->get('Signature');
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

    public function getProviderTimeZone()
    {
        return $this->get('ProviderTimeZone');
    }

    public function getProviderTranDt()
    {
        return $this->get('ProviderTranDt');
    }

    public function getCurrency()
    {
        return $this->get('Currency');
    }

    public function getResultId()
    {
        return $this->get('ResultId');
    }

    public function getRoundId()
    {
        return $this->get('RoundId');
    }

    public function getRoundType()
    {
        return $this->get('RoundType');
    }

    public function getUserId()
    {
        $player = $this->getPlayerId();

        $user = User::where('user_name', $player)->first();

        return $user->id;
    }

    public function getAuthToken()
    {
        return $this->get('AuthToken');
    }

    public function getTranDateTime()
    {
        return $this->get('TranDateTime');
    }
}
