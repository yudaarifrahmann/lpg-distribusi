<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;

class AuditLogController extends Controller
{
    public function index(Request $request)
    {
        $query = ActivityLog::with('user');

        if ($request->filled('module')) {
            $query->where('module', $request->module);
        }

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        $logs = $query->latest()->paginate(50);

        return view('audit-log.index', compact('logs'));
    }

    public function show(ActivityLog $audit_log)
    {
        return view('audit-log.show', compact('audit_log'));
    }
}
