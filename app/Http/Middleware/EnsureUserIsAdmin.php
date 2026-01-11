<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use App\Models\AuditLog;

class EnsureUserIsAdmin
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Check if user is logged in AND is an admin
        if (Auth::check() && Auth::user()->role === 'admin') {
            
            // LOG ADMIN ACTIONS: Log only if modifying data (ignore GET requests)
            if (! $request->isMethod('get')) {
                AuditLog::create([
                    'action' => 'Admin Action',
                    'user_email' => Auth::user()->email,
                    'ip_address' => $request->ip(),
                    'details' => "Performed {$request->method()} action on: {$request->path()}",
                ]);
            }

            return $next($request); // Allow access
        }

        // 2. DETECT SUSPICIOUS ACTIVITY: Unauthorized Admin Access
        AuditLog::create([
            'action' => 'Suspicious Activity',
            'user_email' => Auth::check() ? Auth::user()->email : ($request->input('email') ?? 'Guest'),
            'ip_address' => $request->ip(),
            'details' => 'Unauthorized access attempt to Admin Area.',
        ]);

        // 3. Block them
        abort(403, 'Unauthorized access.');
    }
}