<?php

namespace App\Http\Controllers;

use App\Services\ReportService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReportController extends Controller
{
    public function __construct(
        private ReportService $reportService
    ) {}

    public function index(): View
    {
        return view('reports.index', [
            'inventoryReport' => $this->reportService->inventoryReport(),
        ]);
    }

    public function exportExcel(Request $request)
    {
        $data = $this->reportService->inventoryReport();

        $csv = collect($data)->map(fn($row) => implode(',', $row))->join("\n");
        $headers = implode(',', array_keys($data[0] ?? []));

        return response("{$headers}\n{$csv}", 200, [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => 'attachment; filename="inventory-report.csv"',
        ]);
    }
}