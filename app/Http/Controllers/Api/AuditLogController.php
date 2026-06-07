<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\AuditLogResource;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class AuditLogController extends Controller
{
    /**
     * Display a listing of audit logs.
     */
    public function index(Request $request)
    {
        $query = AuditLog::with('user');

        // Filter by user
        if ($request->filled('user_id')) {
            $query->byUser($request->user_id);
        }

        // Filter by action
        if ($request->filled('action')) {
            $query->action($request->action);
        }

        // Filter by model type
        if ($request->filled('model')) {
            $modelClass = 'App\\Models\\' . $request->model;
            $query->forModel($modelClass);
        }

        // Filter by date range
        if ($request->filled('from_date')) {
            $query->where('created_at', '>=', $request->from_date);
        }
        if ($request->filled('to_date')) {
            $query->where('created_at', '<=', $request->to_date . ' 23:59:59');
        }

        // Default ordering - most recent first
        $query->orderBy('created_at', 'desc');

        $perPage = min($request->get('per_page', 25), 100);

        return AuditLogResource::collection($query->paginate($perPage));
    }

    /**
     * Display the specified audit log.
     */
    public function show(AuditLog $auditLog): AuditLogResource
    {
        return new AuditLogResource($auditLog->load('user'));
    }

    /**
     * Remove the specified audit log from storage.
     */
    public function destroy(AuditLog $auditLog): JsonResponse
    {
        $auditLog->delete();

        return response()->json([
            'message' => 'Audit log deleted successfully.'
        ]);
    }

    /**
     * Remove all audit logs from storage.
     */
    public function deleteAll(): JsonResponse
    {
        AuditLog::truncate();

        return response()->json([
            'message' => 'All audit logs have been successfully cleared.'
        ]);
    }
}
