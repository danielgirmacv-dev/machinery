<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AuditLogController extends Controller
{
    public function index(Request $request): View
    {
        abort_unless(auth()->user()->isAdmin(), 403);

        $query = AuditLog::with('user');

        if ($request->filled('action')) {
            $query->action($request->action);
        }

        if ($request->filled('model')) {
            $modelClass = 'App\\Models\\' . $request->model;
            $query->forModel($modelClass);
        }

        $auditLogs = $query->orderByDesc('created_at')->paginate(25)->withQueryString();

        return view('audit-logs.index', compact('auditLogs'));
    }

    public function destroy(AuditLog $auditLog): RedirectResponse
    {
        abort_unless(auth()->user()->isAdmin(), 403);

        $auditLog->delete();

        return redirect()->route('audit-logs.index')->with('success', 'Audit log deleted successfully.');
    }

    public function deleteAll(): RedirectResponse
    {
        abort_unless(auth()->user()->isAdmin(), 403);

        AuditLog::truncate();

        return redirect()->route('audit-logs.index')->with('success', 'All audit logs have been successfully cleared.');
    }
}
