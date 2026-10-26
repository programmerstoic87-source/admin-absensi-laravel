<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();

        if (!in_array($user->jabatan, ['Admin', 'Supervisor', 'Chief'], true)) {
            abort(403, 'Akses ditolak.');
        }

        return $next($request);
    }
}
