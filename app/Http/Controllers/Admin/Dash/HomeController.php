<?php

namespace App\Http\Controllers\Admin\Dash;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    private const ADMIN_DASH = 'admin.dash.';

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view(self::ADMIN_DASH . 'index');
    }
}
