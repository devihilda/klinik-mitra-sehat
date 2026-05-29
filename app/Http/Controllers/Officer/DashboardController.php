<?php

namespace App\Http\Controllers\Officer;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display the officer/admin dashboard.
     */
    public function index(): View
    {
        return view('officers.dashboard');
    }
}
