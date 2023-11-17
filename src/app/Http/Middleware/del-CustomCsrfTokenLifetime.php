<?php

namespace App\Http\Middleware;

use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Cookie;

// use Illuminate\Support\Facades\Cookie;

class CustomCsrfTokenLifetime
{
    protected $addHttpCookie = true;

    public function handle($request, Closure $next)
    {
        $response = $next($request);

        return $this->addCookieToResponse($response, $request);
    }

    protected function addCookieToResponse($response, $request)
    {
        $config = config('session');

        $response->headers->setCookie(
            new Cookie(
                'XSRF-TOKEN',
                $request->session()->token(),
                Carbon::now()->addMinutes($config['lifetime']),
                $config['path'],
                $config['domain'],
                $config['secure'],
                false,
                false,
                null
            )
        );

        return $response;
    }
}
