<?php

namespace App\Http\Middleware;

use App\Utils\System\GetSetCookie;
use Closure;
use Illuminate\Contracts\Encryption\Encrypter;
use Illuminate\Cookie\CookieValuePrefix;
use Illuminate\Http\Request;

class VerifyToken
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
        if ($request->hasCookie('tlc_token')) {
            // $token = $request->cookie('tlc_token');
            $request->headers->add([
                'Authorization' => 'Bearer ' . GetSetCookie::getCookie('tlc_token'),
            ]);
        }
        return $next($request);
    }
}
