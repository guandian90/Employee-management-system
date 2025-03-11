<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class StepAccessMiddleware
{
    public function handle($request, Closure $next)
    {
        $stepId = $request->route('step');
        $userStep = Auth::user()->current_step_id;

        if ($stepId != $userStep) {
            return response()->json(['error' => '无权限访问此步骤'], 403);
        }

        return $next($request);
    }
}
