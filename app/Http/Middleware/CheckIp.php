<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckIp
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $allowedIps = ['192.168.1.21',
        '127.0.0.1',
        '192.168.1.108',
        '192.168.1.105',
        '192.168.1.101',
        '192.168.1.107',
        '192.168.1.103', 
        '192.168.1.106',
        '192.168.1.104',
        '192.168.68.98',
        '192.168.254.104',
        '143.44.216.31',
        '2001:fd8:b986:c700:c5c0:173f:8fc:aef8'
        ];
        $ip = request()->getClientIp();

        $user = $request->user();
        if ($user && in_array($user->role, ['admin', 'super_admin'])) {
           
            return $next($request);
        }

        if (!in_array($ip, $allowedIps)) {
            // dd($ip);
            abort(403, 'Unauthorized (You are not in the place to access this page)');
        }

        return $next($request);
    }
}
