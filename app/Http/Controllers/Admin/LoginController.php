<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\PaymentType;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function index(): View
    {
        return view('auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $request->validate([
            'user_name' => 'required|string',
            'password' => 'required|string',
        ]);
        $credentials = $this->credentials($request);

        if (! Auth::attempt($credentials)) {
            return back()->with('error', 'The credentials does not match our records.');
        }

        return redirect()->route('home');
    }

    public function logout(): RedirectResponse
    {
        Auth::logout();

        return redirect()->route('login');
    }

    protected function credentials(Request $request)
    {
        return $request->only('user_name', 'password');
    }
}
