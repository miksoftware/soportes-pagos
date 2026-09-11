<?php

namespace App\Http\Controllers;

use App\Models\SoportePago;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display the application dashboard.
     */
    public function index(): View
    {
        $totalSoportes = SoportePago::count();
        $soportesHoy = SoportePago::whereDate('created_at', today())->count();
        $soportesAyer = SoportePago::whereDate('created_at', today()->subDay())->count();
        $soportesMes = SoportePago::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        $totalImagenes = SoportePago::where('tipo', 'imagen')->count();
        $totalPdfs = SoportePago::where('tipo', 'pdf')->count();

        // 7 days trend for Chart.js
        $chartLabels = [];
        $chartData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $chartLabels[] = $date->format('d M');
            $chartData[] = SoportePago::whereDate('created_at', $date->format('Y-m-d'))->count();
        }

        $recentSoportes = SoportePago::latest()->take(5)->get();

        return view('dashboard', compact(
            'totalSoportes',
            'soportesHoy',
            'soportesAyer',
            'soportesMes',
            'totalImagenes',
            'totalPdfs',
            'chartLabels',
            'chartData',
            'recentSoportes'
        ));
    }
}
