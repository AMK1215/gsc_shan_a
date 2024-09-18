<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\PaymentType;
use Illuminate\Contracts\View\View;

class PaymentTypeController extends Controller
{
    public function index(): View
    {
        $paymentTypes = PaymentType::all();

        return view('admin.paymentType.index', compact('paymentTypes'));
    }
}
