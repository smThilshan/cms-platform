<?php

namespace App\Http\Middleware;

use App\Enums\Privilege;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPrivilege
{
    // Full implementation in Phase 2
    public function handle(Request $request, Closure $next, string $privilege): Response
    {
        $user = $request->user();

        if (! $user || ! $user->hasPrivilege(Privilege::from($privilege))) {
            return response()->json(['message' => 'Forbidden.'], 403);
        }

        return $next($request);
    }
}
