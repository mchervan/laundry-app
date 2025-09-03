<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckBranchAccess
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();
        
        // Admin bisa akses semua
        if ($user->isAdmin()) {
            return $next($request);
        }
        
        // Kasir hanya bisa akses cabang mereka
        $requestedBranch = $request->route('branch') ?? $request->input('branch');
        
        if ($user->isKasir() && $user->branch !== $requestedBranch) {
            abort(403, 'Akses ditolak. Anda tidak memiliki akses ke cabang ini.');
        }
        
        return $next($request);
    }
}