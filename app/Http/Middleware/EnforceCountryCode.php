<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnforceCountryCode
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        
        // Only enforce for non-super admins on state-changing requests
        if ($user && !$user->isSuperAdmin() && in_array($request->method(), ['POST', 'PUT', 'PATCH', 'DELETE'])) {
            // For create/update requests
            if (in_array($request->method(), ['POST', 'PUT', 'PATCH'])) {
                $request->merge([
                    'country_code' => $user->country_code
                ]);
            }
            
            // For delete requests, we'll verify access in the controller/service
        }

        return $next($request);
    }
}