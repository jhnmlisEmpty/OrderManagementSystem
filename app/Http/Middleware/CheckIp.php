<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\IpAddress;

class CheckIp
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $allowedIps = IpAddress::pluck('ip_address')->toArray();
        $ip = request()->getClientIp();

        $user = $request->user();
        if ($user && in_array($user->role, ['admin', 'super_admin'])) {
            return $next($request);
        }

        if (!in_array($ip, $allowedIps)) {
            abort(403, 'Unauthorized (Your IP: ' . $ip . ' is not allowed to access this page) contact the developer to register your IP address');
        }

        return $next($request);
    }
}
