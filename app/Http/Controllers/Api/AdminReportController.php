<?php

namespace App\Http\Controllers\API;

use Illuminate\Routing\Controller as BaseController;
use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class AdminReportController extends BaseController
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
        $this->middleware('admin');
    }

    /**
     * Get all reports (admin only)
     */
    public function index(Request $request): JsonResponse
    {
        $query = Report::with(['reporter', 'reportable']);

        // Filter by status
        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Filter by type
        if ($request->has('type') && $request->type !== 'all') {
            $query->where('reportable_type', $request->type === 'user' ? 'App\\Models\\User' : 'App\\Models\\Photo');
        }

        $reports = $query->latest()->paginate(20);

        return response()->json([
            'success' => true,
            'data' => $reports
        ]);
    }

    /**
     * Update report status (admin only)
     */
    public function updateStatus(Request $request, Report $report): JsonResponse
    {
        $request->validate([
            'status' => 'required|in:reviewed,resolved,dismissed',
            'admin_note' => 'nullable|string|max:500'
        ]);

        $report->update([
            'status' => $request->status,
            'admin_note' => $request->admin_note
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Report status updated successfully',
            'data' => $report->load(['reporter', 'reportable'])
        ]);
    }

    /**
     * Get report statistics (admin only)
     */
    public function statistics(): JsonResponse
    {
        $stats = [
            'total_reports' => Report::count(),
            'pending_reports' => Report::where('status', 'pending')->count(),
            'resolved_reports' => Report::where('status', 'resolved')->count(),
            'dismissed_reports' => Report::where('status', 'dismissed')->count(),
            'reports_by_reason' => Report::selectRaw('reason, COUNT(*) as count')
                ->groupBy('reason')
                ->orderBy('count', 'desc')
                ->get(),
            'reports_by_type' => [
                'users' => Report::where('reportable_type', 'App\\Models\\User')->count(),
                'photos' => Report::where('reportable_type', 'App\\Models\\Photo')->count()
            ]
        ];

        return response()->json([
            'success' => true,
            'data' => $stats
        ]);
    }
}
