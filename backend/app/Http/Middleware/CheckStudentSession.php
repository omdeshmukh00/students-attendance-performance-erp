<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckStudentSession
{
    public function handle(Request $request, Closure $next)
    {
        $sessionId = session('student_id');
        $routeId = $request->route('id') ?? $request->route('bt_id');
        if (!$sessionId || $sessionId !== $routeId) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        return $next($request);
    }
}