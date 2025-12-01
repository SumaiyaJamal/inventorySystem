<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
class DashboardController extends Controller
{
    public function index()
    {
        return view('dashboard.index');
    }

    public function logout()
    {
        Auth::logout();

        return redirect('/login'); // optional redirect
    }
}
