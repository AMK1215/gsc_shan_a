<?php

namespace App\Http\Requests;

use App\Models\Admin\GameType;
use App\Models\Admin\SeamlessTransaction;
use App\Models\GameList;
use App\Models\User;
use App\Services\GameResultWebhookValidator;
use App\Services\PlaceBetWebhookValidator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class GameResultWebhookRequest extends FormRequest
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
        $validator = GameResultWebhookValidator::class::make($this)->validate();

        return $validator;
    }

    public function getMember()
    {
        $playerId = $this->getMemberName();

        return User::where('user_name', $playerId)->first();
    }

    // public function getAgentId()
    // {
    //     $player = $this->getPlayerId();

    //     $user = User::where('user_name', $player)->first();

    //     return $user->agent_id;
    // }

    public function getAgentId()
{
    $player = $this->getPlayerId();

    // Safely fetch the player using the 'user_name'
    $user = User::where('user_name', $player)->first();

    // Check if the user exists and has an 'agent_id'
    if ($user && $user->agent_id) {
        return $user->agent_id;
    }

    // Log a warning or handle cases where agent_id is not found
    Log::warning('Agent ID not found for player', ['player' => $player]);

    // Return a default value or handle the missing agent_id as necessary
    return null;
}



    public function getMemberName()
    {
        return $this->get('PlayerId');
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

    // public function transactionId()
    // {
    //     $bet_id = $this->GetBetID();

    //     return SeamlessTransaction::where('bet_id', $bet_id)->first();
    // }
    // public function transactionId()
    // {
    //     $bet_id = $this->GetBetID();
    //     $transaction = SeamlessTransaction::where('bet_id', $bet_id)->first();

    //     // Detailed log to debug the issue
    //     Log::info('Transaction ID lookup:', [
    //         'bet_id' => $bet_id,
    //         'transaction_found' => $transaction ? $transaction->toArray() : 'No transaction found',
    //     ]);

    //     return $transaction->bet_id;
    // }

    public function transaction()
    {
        $bet_id = $this->GetBetID();
        $transaction = SeamlessTransaction::where('bet_id', $bet_id)->first();

        // Log detailed transaction lookup information
        Log::info('Transaction ID lookup:', [
            'bet_id' => $bet_id,
            'transaction_found' => $transaction ? $transaction->toArray() : 'No transaction found',
        ]);

        return $transaction;
    }

    public function GetBetID()
    {
        return $this->get('BetId');
    }

    public function getResultId()
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
    //     public function getAuthToken()
    // {
    //     // If there's an authenticated user and they have a current access token
    //     if (Auth::check() && Auth::user()->currentAccessToken()) {
    //         return Auth::user()->currentAccessToken()->token;
    //     }

    //     // Fallback to using the AuthToken provided in the request
    //     return $this->get('AuthToken');
    // }

    public function getTranDateTime()
    {
        return $this->get('TranDateTime');
    }
}