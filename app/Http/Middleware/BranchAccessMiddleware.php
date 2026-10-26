<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class BranchAccessMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        if (in_array($user->jabatan, ['Admin', 'Chief'], true)) {
            return $next($request);
        }

        $branchId = $request->route('branch_id') ?? $request->branch_id;

        if ($branchId && $user->branch_id == $branchId) {
            return $next($request);
        }

        abort(403, 'Anda tidak punya akses ke cabang ini.');
    }
}
