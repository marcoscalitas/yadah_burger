<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PasswordController extends Controller
{
    private const ADMIN_AUTH_PASSWORD = 'admin.auth.password.';

    public function forgot()
    {
        return view(self::ADMIN_AUTH_PASSWORD . 'forgot');
    }

    public function checkEmail()
    {
        return view(self::ADMIN_AUTH_PASSWORD . 'check-email');
    }

    public function reset()
    {
        return view(self::ADMIN_AUTH_PASSWORD . 'reset');
    }
}
