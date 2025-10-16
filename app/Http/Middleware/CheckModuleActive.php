<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckModuleActive
{
    /**
     * Handle an incoming request.
     * Middleware accepts an optional parameter: module id.
     */
    public function handle(Request $request, Closure $next, $moduleId = null)
    {
        $user = $request->user();

        if (! $user) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }

        $id = $moduleId ?? $request->route('module_id') ?? $request->route('id');

        if (! $id) {
            return $next($request);
        }

        $has = $user->modules()->where('modules.id', $id)->wherePivot('active', true)->exists();

        if (! $has) {
            return response()->json(['error' => 'Module inactive. Please activate this module to use it.'], 403);
        }

        return $next($request);
    }
}
