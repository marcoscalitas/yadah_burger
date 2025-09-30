<?php

namespace App\Http\Controllers\Admin\Dash;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserController extends Controller
{
    private const ADMIN_DASH_USERS = 'admin.dash.users.';

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view(self::ADMIN_DASH_USERS . 'index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view(self::ADMIN_DASH_USERS . 'create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
