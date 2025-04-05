<?php
// app/Http/Middleware/SecurityHeaders.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SecurityHeaders
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        // Content Security Policy (CSP) Headers
        if (config('security.csp_enabled', true)) {
            $cspDirectives = config('security.csp_directives', []);
            $cspHeader = '';

            foreach ($cspDirectives as $directive => $sources) {
                $cspHeader .= $directive . ' ' . implode(' ', $sources) . '; ';
            }

            $response->headers->set('Content-Security-Policy', $cspHeader);
        }

        // X-Content-Type-Options header prevents browsers from MIME-sniffing a response
        $response->headers->set('X-Content-Type-Options', 'nosniff');

        // X-Frame-Options header prevents your site from being embedded in frames (clickjacking protection)
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');

        // X-XSS-Protection header enables the Cross-site scripting (XSS) filter
        $response->headers->set('X-XSS-Protection', '1; mode=block');

        // Referrer-Policy header controls how much referrer information is sent
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');

        // Permissions-Policy header (formerly Feature-Policy) restricts browser features
        $response->headers->set('Permissions-Policy', 'camera=(), microphone=(), geolocation=()');

        // Strict-Transport-Security header forces HTTPS on modern browsers
        if (!app()->environment('local')) {
            $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains');
        }

        return $response;
    }
}