<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;

class Redirect
{
    /**
     * Handle an incoming request.
     *
     * @param \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response) $next
     */
    public function handle(Request $request, Closure $next)
    {
        $session = new Session();
        $access = $session->get('access_granted');
        if ($access) {
            if ($request->is('/')) {
                return redirect()->route('app');
            }
        } else {
            if ($request->is('/app')) {
                return redirect()->route('auth');
            }
        }
        return $next($request);
    }
}
