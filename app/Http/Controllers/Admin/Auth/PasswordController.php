<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Models\User;
use App\Helpers\Helper;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;


class PasswordController extends Controller
{
    private const ADMIN_AUTH_PASSWORD = 'admin.auth.password.';

    public function showForgotForm()
    {
        return view(self::ADMIN_AUTH_PASSWORD . 'forgot');
    }

    public function sendResetLink(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
            ? redirect()->route('admin.check.email')->with(['email' => $request->get("email")])
            : back()->withErrors(['email' => __($status)]);
    }

    public function showCheckEmail(Request $request)
    {
        $email = session('email');
        return view(self::ADMIN_AUTH_PASSWORD . 'check-email', compact('email'));
    }

    public function showResetForm(Request $request, $token)
    {
        return view(self::ADMIN_AUTH_PASSWORD . 'reset', [
            'token' => $token,
            'email' => $request->email
        ]);
    }

    public function reset(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|string|min:8|confirmed'
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                    'remember_token' => Str::random(60),
                ])->save();
            }
        );

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('admin.login')->with('success', __($status))
            : back()->withErrors(['email' => [__($status)]]);
    }
}
