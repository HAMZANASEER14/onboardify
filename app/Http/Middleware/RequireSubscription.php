<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Subscription;

class RequireSubscription
{
    public function handle(Request $request, Closure $next)
    {
        $user = auth()->user();

        // Only enforce this for admins — employees/clients don't own subscriptions
        if ($user->role === 'admin') {
            $hasActive = Subscription::where('user_id', $user->id)
                ->where('status', 'active')
                ->exists();

            if (!$hasActive) {
                return redirect()->route('plans')
                    ->with('warning', 'Please select a plan to continue.');
            }
        }

        return $next($request);
    }
}