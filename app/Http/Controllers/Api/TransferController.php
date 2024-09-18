<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\DepositRequest;
use App\Http\Resources\DepositHistoryResource;
use App\Http\Resources\WithDrawHistoryResource;
use App\Models\Admin\Deposit;
use App\Models\Admin\WithdrawRequest;
use App\Traits\HttpResponses;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class TransferController extends Controller
{
    use HttpResponses;

    public function withdraw(\App\Http\Requests\WithdrawRequest $request)
    {
        try {
            $inputs = $request->validated();
            $player = Auth::user();

            if (! $player || ! Hash::check($request->password, $player->password)) {
                return $this->error('', 'လျို့ဝှက်နံပါတ်ကိုက်ညီမှု မရှိပါ။', 401);
            }

            if ($player->wallet->balance < $inputs['amount']) {
                return $this->error('', 'Insufficient Balance', '401');
            }

            $withdraw = WithdrawRequest::create([
                'amount' => $inputs['amount'],
                'user_id' => $player->id,
                'agent_id' => $player->agent_id,
                'account_number' => $inputs['account_number'],
                'account_name' => $inputs['account_name'],
                'payment_type_id' => $inputs['payment_type_id'],
            ]);

            return $this->success($withdraw, 'Withdraw Success');
        } catch (\Exception $e) {
            $this->error('', $e->getMessage(), 401);
        }
    }

    public function deposit(DepositRequest $request): JsonResponse
    {
        $player = Auth::user();

        $deposit = Deposit::create([
            'amount' => $request->amount,
            'bank_id' => $request->agent_bank_id,
            'reference_number' => $request->reference_number,
            'user_id' => $player->id,
            'agent_id' => $player->agent_id,
        ]);

        return $this->success($deposit, 'Deposit Success');
    }

    public function depositHistory(): JsonResponse
    {
        $player = Auth::user();
        $deposit = Deposit::where('user_id', $player->id)->get();

        return $this->success(DepositHistoryResource::collection($deposit), 'Deposit History');
    }

    public function withdrawHistory(): JsonResponse
    {
        $player = Auth::user();
        $withdraw = WithdrawRequest::where('user_id', $player->id)->get();

        return $this->success(WithdrawhistoryResource::collection($withdraw), 'Withdraw History');
    }
}
