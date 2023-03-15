<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class Broadcast
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $authWeb = auth()->guard('web')->user();
        if ($authWeb) {
            return response()->json(\Illuminate\Support\Facades\Broadcast::auth($request));
        }
        return response()->json('Unauthorized.', 500);
        // return $next($request);
    }
}
