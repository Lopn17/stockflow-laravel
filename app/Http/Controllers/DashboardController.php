<?php

namespace App\Http\Controllers;

use App\Services\DashboardService;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __construct(
        private DashboardService $dashboard
    ) {}

    public function index(): View
    {
        return view('dashboard.index', [
            'stats'              => $this->dashboard->getStats(),
            'lowStockProducts'   => $this->dashboard->getLowStockProducts(),
            'recentTransactions' => $this->dashboard->getRecentTransactions(),
            'stockChart'         => $this->dashboard->getStockMovementChart(),
            'topProducts'        => $this->dashboard->getTopProductsByValue(),
        ]);
    }
}