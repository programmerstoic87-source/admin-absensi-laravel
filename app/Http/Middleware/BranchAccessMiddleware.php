<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class BranchAccessMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();

        if ($user->jabatan === 'Admin' || $user->jabatan === 'Chief') {
            return $next($request);
        }

        $branchId = $request->route('branch_id') ?? $request->branch_id;

        if ($branchId && $user->branch_id == $branchId) {
            return $next($request);
        }

        abort(403, 'Anda tidak punya akses ke cabang ini.');
    }
}
