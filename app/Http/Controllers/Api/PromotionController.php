<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Resources\PromotionResource;
use App\Http\Resources\UserResource;
use App\Models\Admin\PaymentType;
use App\Models\Admin\Promotion;
use App\Models\Admin\UserLog;
use App\Models\User;
use App\Traits\HttpResponses;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class PromotionController extends Controller
{
    use HttpResponses;

    public function index(): JsonResponse
    {
        $promotions = Promotion::where('agent_id', Auth::user()->agent_id)->get();

        return $this->success(PromotionResource::collection($promotions), 'Promotions retrieved successfully.');
    }
}
