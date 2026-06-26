<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AnnouncementController extends Controller
{
    /**
     * GET /api/v1/announcements
     *
     * Returns published announcements for the student role.
     * Filters to 'all' and 'student' targeted announcements.
     */
    public function index(Request $request): JsonResponse
    {
        $announcements = Announcement::whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->whereIn('target_role', ['all', 'student'])
            ->orderBy('published_at', 'desc')
            ->paginate(15);

        return response()->json([
            'data' => $announcements->map(fn ($announcement) => [
                'id'           => $announcement->id,
                'title'        => $announcement->title,
                'body'         => $announcement->body,
                'published_at' => $announcement->published_at?->toIso8601String(),
            ]),
            'meta' => [
                'current_page' => $announcements->currentPage(),
                'last_page'    => $announcements->lastPage(),
                'total'        => $announcements->total(),
            ],
        ]);
    }

    /**
     * GET /api/v1/announcements/{announcement}
     *
     * Returns a single announcement. Ensures it is published and targeted at students.
     */
    public function show(Request $request, Announcement $announcement): JsonResponse
    {
        if (
            ! $announcement->published_at ||
            $announcement->published_at > now() ||
            ! in_array($announcement->target_role, ['all', 'student'])
        ) {
            abort(404, 'Announcement not found.');
        }

        return response()->json([
            'data' => [
                'id'           => $announcement->id,
                'title'        => $announcement->title,
                'body'         => $announcement->body,
                'target_role'  => $announcement->target_role,
                'published_at' => $announcement->published_at->toIso8601String(),
            ],
        ]);
    }
}
