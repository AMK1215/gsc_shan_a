<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\BankRequest;
use App\Models\Admin\Bank;
use App\Models\Admin\Banner;
use App\Models\Admin\PaymentType;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Redirect;

class BankController extends Controller
{
    public function index(): View
    {
        $banks = Bank::where('user_id', Auth::id())->get();

        return view('admin.bank.index', compact('banks'));
    }

    public function create(): View
    {
        $paymentTypes = PaymentType::all();

        return view('admin.bank.create', compact('paymentTypes'));
    }

    public function store(BankRequest $request): RedirectResponse
    {
        $bank = Bank::where('user_id', Auth::id())->where('payment_type_id', $request->payment_type_id)->first();

        if ($bank) {
            return \redirect()->back()->with('error', 'Already exists bank account');
        }

        Bank::create([
            'account_name' => $request->account_name,
            'account_number' => $request->account_number,
            'payment_type_id' => $request->payment_type_id,
            'user_id' => Auth::id(),
        ]);

        return \redirect()->route('admin.bank.index')
            ->with('success', 'New bank Added.');
    }

    public function edit(Bank $bank): View
    {
        $paymentTypes = PaymentType::all();

        return view('admin.bank.edit', compact('bank', 'paymentTypes'));
    }

    public function update(Request $request, Bank $bank): RedirectResponse
    {
        $bank->update($request->all());

        return \redirect()->route('admin.bank.index')
            ->with('success', 'Banner Image Updated.');
    }

    public function destroy(Bank $bank): RedirectResponse
    {
        $bank->delete();

        return redirect()->back()->with('success', 'bank Deleted.');
    }
}
