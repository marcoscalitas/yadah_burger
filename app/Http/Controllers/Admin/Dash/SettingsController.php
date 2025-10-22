<?php

namespace App\Http\Controllers\Admin\Dash;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class SettingsController extends Controller
{
    private const ADMIN_DASH_SETTINGS = 'admin.dash.settings.';

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view(self::ADMIN_DASH_SETTINGS.'index');
    }

    public function changePassword()
    {
        return view(self::ADMIN_DASH_SETTINGS.'change-password');
    }

    public function updatePassword(Request $request)
    {
        $messages = getPasswordValidationMessages();

        // Validação principal
        $validatedData = $request->validate([
            'current_password' => 'required',
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
                function ($attribute, $value, $fail) use ($messages) {
                    validatePassword($value, $fail, [
                        'a_z' => $messages['password.regex.a_z'],
                        'A_Z' => $messages['password.regex.A_Z'],
                        '0_9' => $messages['password.regex.0_9'],
                        'special' => $messages['password.regex.special'],
                    ]);
                },
            ],
            'password_confirmation' => 'required',
        ], $messages);

        $user = getCurrentUser('admin');
        if (! Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Senha atual incorreta'])->withInput();
        }

        $user->password = Hash::make($request->password);
        $user->save();

        return redirect()->route('admin.settings.change.password')->with('success', 'Senha alterada com sucesso!');
    }

    public function changeEmail()
    {
        return view(self::ADMIN_DASH_SETTINGS.'change-email');
    }

    public function updateEmail(Request $request)
    {
        $user = getCurrentUser('admin');

        $request->validate([
            'current_password' => ['required'],
            'new_email' => ['required', 'email', Rule::unique('users', 'email')->ignore($user->id)],
        ], [
            'current_password.required' => 'Informe sua senha atual.',
            'new_email.required' => 'Informe um novo e-mail.',
            'new_email.email' => 'O e-mail informado não é válido.',
            'new_email.unique' => 'Este e-mail já está em uso.',
        ]);

        if (! Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Senha incorreta.']);
        }

        $user->email = $request->new_email;
        $user->email_verified_at = null;
        $user->save();

        session(['email_verified' => false]);

        return redirect()
            ->route('admin.settings.change.email')
            ->with('success', 'E-mail atualizado com sucesso! Confirme seu novo e-mail para continuar.');
    }

    public function deleteAccount(Request $request)
    {
        $auth = auth('admin');
        $user = $auth->user();

        $auth->logout();
        $user->delete();

        return redirect()->route('admin.login')->with('success', 'Sua conta foi excluída com sucesso.');
    }
}
