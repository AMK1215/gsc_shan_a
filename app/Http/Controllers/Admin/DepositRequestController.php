<?php

namespace App\Http\Controllers\Admin;

use App\Enums\TransactionName;
use App\Http\Controllers\Controller;
use App\Models\Admin\Deposit;
use App\Models\User;
use App\Services\WalletService;
use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DepositRequestController extends Controller
{
    public function index(): View
    {
        $deposits = Deposit::where('agent_id', Auth::id())->latest()->get();

        return view('admin.depositRequest.index', compact('deposits'));
    }

    public function reject(Request $request, Deposit $deposit): RedirectResponse
    {
        $request->validate([
            'status' => 'required|in:0,1,2',
        ]);

        try {
            $deposit->update([
                'status' => $request->status,
            ]);

            return redirect()->back()->with('success', 'Deposit status updated successfully!');
        } catch (Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function approve(Request $request, Deposit $deposit): RedirectResponse
    {
        $request->validate([
            'status' => 'required|in:0,1,2',
            'amount' => 'required|numeric|min:0',
            'player' => 'required|exists:users,id',
        ]);

        try {
            $agent = Auth::user();
            $player = User::find($request->player);

            if ($request->status == 1 && $agent->wallet->balance < $request->amount) {
                return redirect()->back()->with('error', 'You do not have enough balance to transfer!');
            }

            $deposit->update([
                'status' => $request->status,
            ]);

            if ($request->status == 1) {
                app(WalletService::class)->transfer($agent, $player, $request->amount, TransactionName::CreditTransfer);
            }

            return redirect()->back()->with('success', 'Deposit status updated successfully!');
        } catch (Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
