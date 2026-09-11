<?php

namespace App\Http\Controllers;

use App\Models\SoportePago;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display the application dashboard.
     */
    public function index(): View
    {
        $totalUsers = User::count();
        $adminUsers = User::where('role', 'admin')->count();
        $regularUsers = User::where('role', 'user')->count();
        $recentUsers = User::latest()->take(5)->get();

        $totalSoportes = SoportePago::count();
        $recentSoportes = SoportePago::latest()->take(6)->get();

        return view('dashboard', compact(
            'totalUsers',
            'adminUsers',
            'regularUsers',
            'recentUsers',
            'totalSoportes',
            'recentSoportes'
        ));
    }
}
