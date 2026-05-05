<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $userId = session('user_id');
        $user = $userId ? User::find($userId) : null;

        if (! $user || ! $user->is_admin) {
            abort(403, 'Admin access required.');
        }

        return $next($request);
    }
}
