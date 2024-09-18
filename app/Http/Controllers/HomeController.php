<?php

namespace App\Http\Controllers;

use App\Enums\TransactionName;
use App\Enums\UserType;
use App\Models\Admin\UserLog;
use App\Models\SeamlessTransaction;
use App\Models\User;
use App\Services\WalletService;
use App\Settings\AppSetting;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $user = Auth::user();
        $role = $user->roles->pluck('title');

        $agent_count = User::where('type', UserType::Agent)->when($role[0] != 'Admin', function ($query) use ($user) {
            $query->where('agent_id', $user->id);
        })->count();

        $player_count = User::where('type', UserType::Player)
            ->when($role[0] === 'Master', function ($query) use ($user) {
                $agentIds = User::where('type', UserType::Agent)
                    ->where('agent_id', $user->id)
                    ->pluck('id');

                return $query->whereIn('agent_id', $agentIds);
            })->when($role[0] === 'Agent', function ($query) use ($user) {
                return $query->where('agent_id', $user->id);
            })
            ->count();

        $totalBalance = DB::table('users')->join('wallets', 'wallets.user_id', '=', 'users.id')
            ->where('agent_id', Auth::id())->select(DB::raw('SUM(wallets.balance) as balance'))->first();

        return view('admin.dashboard', compact(
            'agent_count',
            'player_count',
            'user',
            'totalBalance',
            'role'
        ));
    }

    public function balanceUp(Request $request)
    {
        abort_if(
            Gate::denies('admin_access'),
            Response::HTTP_FORBIDDEN,
            '403 Forbidden |You cannot  Access this page because you do not have permission'
        );
        $request->validate([
            'balance' => 'required|numeric',
        ]);

        app(WalletService::class)->deposit($request->user(), $request->balance, TransactionName::CapitalDeposit);

        return back()->with('success', 'Add New Balance Successfully.');
    }

    public function logs($id)
    {
        $logs = UserLog::with('user')->where('user_id', $id)->get();

        return view('admin.login_logs.index', compact('logs'));
    }

    public function changePassword(Request $request, User $user)
    {
        return view('admin.change_password', compact('user'));
    }

    public function updatePassword(Request $request, User $user)
    {
        $request->validate([
            'password' => 'required|string|min:6|confirmed',
        ]);
        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('home')->with('success', 'Password has been changed Successfully.');
    }

    public function agentList()
    {
        $user = Auth::user();
        $role = $user->roles->pluck('title');
        $users = User::where('type', UserType::Agent)->when($role[0] != 'Admin', function ($query) use ($user) {
            $query->where('agent_id', $user->id);
        })->get();

        return view('admin.agent_list', compact('users'));
    }

    public function playerList()
    {
        $user = Auth::user();
        $role = $user->roles->pluck('title');
        $users = User::where('type', UserType::Player)
            ->when($role[0] === 'Master', function ($query) use ($user) {
                $agentIds = User::where('type', UserType::Agent)
                    ->where('agent_id', $user->id)
                    ->pluck('id');

                return $query->whereIn('agent_id', $agentIds);
            })->when($role[0] === 'Agent', function ($query) use ($user) {
                return $query->where('agent_id', $user->id);
            })
            ->get();

        return view('admin.player_list', compact('users'));
    }
}
