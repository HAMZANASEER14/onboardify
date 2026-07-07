<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckProfile
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        // If user has no profile AND is not already on the profile creation page
        if ($user && !$user->profile && !$request->routeIs('profile.create')) {
            return redirect()->route('profile.create');
        }

        return $next($request);
    }
}