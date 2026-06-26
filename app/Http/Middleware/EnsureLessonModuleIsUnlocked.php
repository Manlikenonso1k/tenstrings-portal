<?php

namespace App\Http\Middleware;

use App\Models\Lesson;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureLessonModuleIsUnlocked
{
    public function handle(Request $request, Closure $next): Response
    {
        $lesson = $request->route('lesson');

        if ($lesson instanceof Lesson) {
            abort_unless($request->user()?->canAccessLesson($lesson) ?? false, 403);
        }

        return $next($request);
    }
}
