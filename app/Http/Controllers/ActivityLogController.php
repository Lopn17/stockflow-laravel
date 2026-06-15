<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ActivityLogController extends Controller
{
    public function index(Request $request): View
    {
        $logs = ActivityLog::with('user')
            ->when(
                $request->search,
                fn($q, $s) => $q->where('description', 'like', "%{$s}%")
                               ->orWhere('action', 'like', "%{$s}%")
            )
            ->latest()
            ->paginate(30);

        return view('logs.index', compact('logs'));
    }
}