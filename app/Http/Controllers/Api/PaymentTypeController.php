<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Resources\BankResource;
use App\Http\Resources\UserResource;
use App\Models\Admin\Bank;
use App\Models\Admin\PaymentType;
use App\Models\Admin\UserLog;
use App\Models\User;
use App\Traits\HttpResponses;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class PaymentTypeController extends Controller
{
    use HttpResponses;

    public function index(): JsonResponse
    {
        $paymentTypes = PaymentType::all();

        return $this->success($paymentTypes, 'Payment Type successfully.');
    }

    public function getAgentBank(): JsonResponse
    {
        $player = Auth::user();

        $banks = Bank::where('user_id', $player->agent_id)->get();

        return $this->success(BankResource::collection($banks), 'Bank successfully.');
    }
}
