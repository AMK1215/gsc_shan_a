<?php

namespace App\Http\Controllers\Admin;

use App\Enums\TransactionName;
use App\Enums\UserType;
use App\Http\Controllers\Controller;
use App\Http\Requests\MasterRequest;
use App\Models\User;
use App\Services\WalletService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;

class MasterController extends Controller
{
    private const MASTER_ROLE = 2;

    public function index(): View
    {
        if (! Gate::allows('master_index')) {
            abort(403);
        }

        $users = User::hasRole(self::MASTER_ROLE)
            ->orderBy('id', 'desc')
            ->get();

        return view('admin.master.index', compact('users'));
    }

    public function create(): View
    {
        if (! Gate::allows('master_create')) {
            abort(403);
        }

        $user_name = $this->generateRandomString();

        return view('admin.master.create', compact('user_name'));
    }

    public function store(MasterRequest $request): RedirectResponse
    {
        if (! Gate::allows('master_create')) {
            abort(403);
        }

        $senior = Auth::user();
        $inputs = $request->validated();

        if (isset($inputs['amount']) && $inputs['amount'] > $senior->wallet->balance) {
            return redirect()->back()->with('error', 'Insufficient balance for transfer.');
        }
        $userPrepare = array_merge(
            $inputs,
            [
                'password' => Hash::make($inputs['password']),
                'agent_id' => Auth::id(),
                'type' => UserType::Master,
            ]
        );

        $master = User::create($userPrepare);
        $master->roles()->sync(self::MASTER_ROLE);

        if (isset($inputs['amount'])) {
            app(WalletService::class)->transfer($senior, $master, $inputs['amount'], TransactionName::CreditTransfer);
        }

        return redirect()->route('admin.master.index')
            ->with('successMessage', 'Master created successfully')
            ->with('user_name', $master->user_name)
            ->with('password', $request->password)
            ->with('amount', $request->amount);
    }

    public function edit(string $id): View
    {
        if (! Gate::allows('master_edit')) {
            abort(403);
        }

        $master = User::find($id);

        return view('admin.master.edit', compact('master'));
    }

    public function update(Request $request, string $id): RedirectResponse
    {
        if (! Gate::allows('master_edit')) {
            abort(403);
        }

        $user = User::find($id);
        $user->update([
            'user_name' => $request->user_name,
            'name' => $request->name,
            'phone' => $request->phone,
        ]);

        return redirect()->back()
            ->with('success', 'Master Updated successfully');
    }

    public function deposit(User $master): View
    {
        return view('admin.master.deposit', compact('master'));
    }

    public function makeDeposit(Request $request, User $master): RedirectResponse
    {
        if (! Gate::allows('make_transfer')) {
            abort(403);
        }
        $request->validate([
            'amount' => 'required|numeric|min:0',
        ]);

        $senior = Auth::user();

        if ($senior->wallet->balance < $request->amount) {
            return redirect()->back()->with(['error' => 'Insufficient balance for transfer.']);
        }

        app(WalletService::class)->transfer($senior, $master, $request->amount, TransactionName::CreditTransfer, $request->note);

        return redirect()->route('admin.master.index')->with('success', 'Master transfer completed successfully');
    }

    public function withdraw(User $master): View
    {
        return view('admin.master.withdraw', compact('master'));
    }

    public function makeWithdraw(Request $request, User $master): RedirectResponse
    {
        if (! Gate::allows('make_transfer')) {
            abort(403);
        }
        $request->validate([
            'amount' => 'required|numeric|min:0',
        ]);

        $senior = Auth::user();

        if ($senior->wallet->balance < $request->amount) {
            return redirect()->back()->with(['error' => 'Insufficient balance for transfer.']);
        }

        app(WalletService::class)->transfer($master, $senior, $request->amount, TransactionName::DebitTransfer, $request->note);

        return redirect()->route('admin.master.index')->with('success', 'Master transfer completed successfully');

    }

    public function ban($id): RedirectResponse
    {
        $user = User::find($id);
        $user->update(['status' => $user->status == 1 ? 2 : 1]);

        return redirect()->back()->with(
            'success',
            'User '.($user->status == 1 ? 'activated' : 'banned').' successfully'
        );
    }

    public function changePassword(User $master): View
    {
        return view('admin.master.change_password', compact('master'));
    }

    public function makePassword(Request $request, User $master): RedirectResponse
    {
        $request->validate([
            'password' => 'required|string|min:6|confirmed',
        ]);
        $master->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('admin.master.index')->with(
            'success',
            'User Password has been changed successfully'
        );
    }

    private function generateRandomString(): string
    {
        $randomNumber = mt_rand(10000000, 99999999);

        return 'M'.$randomNumber;
    }
}
