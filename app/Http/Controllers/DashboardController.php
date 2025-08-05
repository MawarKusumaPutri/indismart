<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Dashboard untuk mitra
     */
    public function mitraDashboard()
    {
        // Pastikan user adalah mitra
        if (!Auth::user()->isMitra()) {
            return redirect('staff/dashboard');
        }

        return view('mitra.dashboard');
    }

    /**
     * Dashboard untuk staff
     */
    public function staffDashboard()
    {
        // Pastikan user adalah staff
        if (!Auth::user()->isStaff()) {
            return redirect('mitra/dashboard');
        }

        return view('staff.dashboard');
    }
}