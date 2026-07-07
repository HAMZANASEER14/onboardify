<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Subscription;

class CheckSubscription
{
    public function handle(Request $request, Closure $next)
    {
        $subscription = Subscription::where('user_id', auth()->id())
                                    ->where('status', 'active')
                                    ->first();

        if ($subscription) {
            return redirect()->route('admin.dashboard')
                           ->with('info', '✅ You already have an active subscription!');
        }

        return $next($request);
    }
}