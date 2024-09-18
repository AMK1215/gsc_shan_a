<?php

namespace App\Http\Controllers\Admin;

use App\Enums\TransactionName;
use App\Enums\UserType;
use App\Http\Controllers\Controller;
use App\Http\Requests\AgentRequest;
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

class AgentController extends Controller
{
    private const AGENT_ROLE = 3;

    public function index(): View
    {
        if (! Gate::allows('agent_index')) {
            abort(403);
        }

        $users = User::with('wallet')->hasRole(self::AGENT_ROLE)
            ->orderBy('id', 'desc')
            ->where('agent_id', Auth::id())
            ->get();

        return view('admin.agent.index', compact('users'));
    }

    public function create()
    {
        if (! Gate::allows('agent_create')) {
            abort(403);
        }

        $user_name = $this->generateRandomString();

        return view('admin.agent.create', compact('user_name'));
    }

    public function store(AgentRequest $request): RedirectResponse
    {
        if (! Gate::allows('agent_create')) {
            abort(403);
        }

        $master = Auth::user();

        $inputs = $request->validated();

        if (isset($inputs['amount']) && $inputs['amount'] > $master->wallet->balance) {
            return redirect()->back()->with(['error' => 'Insufficient balance for transfer.']);
        }
        $userPrepare = array_merge(
            $inputs,
            [
                'password' => Hash::make($inputs['password']),
                'agent_id' => Auth::id(),
                'type' => UserType::Agent,
            ]
        );

        $agent = User::create($userPrepare);
        $agent->roles()->sync(self::AGENT_ROLE);

        if (isset($inputs['amount'])) {
            app(WalletService::class)->transfer($master, $agent, $inputs['amount'], TransactionName::CreditTransfer);
        }

        return redirect()->route('admin.agent.index')
            ->with('successMessage', 'Agent created successfully')
            ->with('user_name', $agent->user_name)
            ->with('password', $request->password);
    }

    public function edit(string $id): View
    {
        if (! Gate::allows('agent_edit')) {
            abort(403);
        }

        $agent = User::find($id);

        return view('admin.agent.edit', compact('agent'));
    }

    public function update(Request $request, string $id): RedirectResponse
    {
        if (! Gate::allows('agent_edit')) {
            abort(403);
        }

        $user = User::find($id);
        $user->update([
            'user_name' => $request->user_name,
            'name' => $request->name,
            'phone' => $request->phone,
        ]);

        return redirect()->route('admin.agent.index')
            ->with('successMessage', 'Agent Updated successfully')
            ->with('user_name', $user->user_name)
            ->with('password', $request->password);
    }

    public function deposit(User $agent): View
    {
        return view('admin.agent.deposit', compact('agent'));
    }

    public function makeDeposit(Request $request, User $agent): RedirectResponse
    {
        if (! Gate::allows('make_transfer')) {
            abort(403);
        }
        $request->validate([
            'amount' => 'required|numeric|min:0',
        ]);

        $master = Auth::user();

        if ($master->wallet->balance < $request->amount) {
            return redirect()->back()->with(['error' => 'Insufficient balance for transfer.']);
        }

        app(WalletService::class)->transfer($master, $agent, $request->amount, TransactionName::CreditTransfer, $request->note);

        return redirect()->route('admin.agent.index')->with('success', 'Agent transfer completed successfully');
    }

    public function withdraw(User $agent): View
    {
        return \view('admin.agent.withdraw', compact('agent'));
    }

    public function makeWithdraw(Request $request, User $agent): RedirectResponse
    {
        if (! Gate::allows('make_transfer')) {
            abort(403);
        }
        $request->validate([
            'amount' => 'required|numeric|min:0',
        ]);

        $master = Auth::user();

        if ($master->wallet->balance < $request->amount) {
            return redirect()->back()->with(['error' => 'Insufficient balance for transfer.']);
        }

        app(WalletService::class)->transfer($agent, $master, $request->amount, TransactionName::DebitTransfer, $request->note);

        return redirect()->route('admin.agent.index')->with('success', 'Agent transfer completed successfully');

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

    public function changePassword(User $agent): View
    {
        return view('admin.agent.change_password', compact('agent'));
    }

    public function makePassword(Request $request, User $agent): RedirectResponse
    {
        $request->validate([
            'password' => 'required|string|min:6|confirmed',
        ]);
        $agent->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('admin.agent.index')->with(
            'success',
            'User Password has been changed successfully'
        );
    }

    private function generateRandomString(): string
    {
        $randomNumber = mt_rand(10000000, 99999999);

        return 'A'.$randomNumber;
    }
}
