<?php

namespace App\Http\Controllers\API;

use Illuminate\Routing\Controller as BaseController;
use App\Models\Report;
use App\Models\User;
use App\Models\Photo;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\Rule;

class ReportController extends BaseController
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    /**
     * Create a new report
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'type' => ['required', 'string', Rule::in(['user', 'photo'])],
            'id' => 'required|integer',
            'reason' => ['required', 'string', Rule::in(array_keys(Report::getReasons()))],
            'description' => 'nullable|string|max:500'
        ]);

        $reporter = auth()->user();

        // Determine the reportable model
        $reportable = null;
        if ($request->type === 'user') {
            $reportable = User::find($request->id);
            if (!$reportable) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not found'
                ], 404);
            }

            if ($reporter->id === $reportable->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'You cannot report yourself'
                ], 400);
            }
        } elseif ($request->type === 'photo') {
            $reportable = Photo::find($request->id);
            if (!$reportable) {
                return response()->json([
                    'success' => false,
                    'message' => 'Photo not found'
                ], 404);
            }

            if ($reporter->id === $reportable->user_id) {
                return response()->json([
                    'success' => false,
                    'message' => 'You cannot report your own photo'
                ], 400);
            }
        }

        // Check if already reported
        $existingReport = Report::where('reporter_id', $reporter->id)
                                ->where('reportable_type', get_class($reportable))
                                ->where('reportable_id', $reportable->id)
                                ->first();

        if ($existingReport) {
            return response()->json([
                'success' => false,
                'message' => 'You have already reported this ' . $request->type
            ], 400);
        }

        // Create the report
        $report = Report::create([
            'reporter_id' => $reporter->id,
            'reportable_type' => get_class($reportable),
            'reportable_id' => $reportable->id,
            'reason' => $request->reason,
            'description' => $request->description
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Report submitted successfully',
            'data' => $report->load('reportable')
        ]);
    }

    /**
     * Get user's report history
     */
    public function index(Request $request): JsonResponse
    {
        $reports = auth()->user()
            ->reports()
            ->with('reportable')
            ->latest()
            ->paginate(20);

        return response()->json([
            'success' => true,
            'data' => $reports
        ]);
    }

    /**
     * Get report details
     */
    public function show(Report $report): JsonResponse
    {
        if (auth()->id() !== $report->reporter_id && !auth()->user()->is_admin) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        return response()->json([
            'success' => true,
            'data' => $report->load(['reporter', 'reportable'])
        ]);
    }
}
