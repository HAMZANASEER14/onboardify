<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Plan;
use App\Models\Subscription;

class PaymentController extends Controller
{
    // ── Show Payment Page ─────────────────────────────────────────
    public function index()
    {
        if (!session()->has('plan_id')) {
            return redirect('/plans');
        }

        $plan = Plan::find(session('plan_id'));

        if (!$plan) {
            return redirect('/plans');
        }

        return view('payment.index', compact('plan'));
    }

    // ── Process Fake Payment ──────────────────────────────────────
    public function processPayment(Request $request)
    {
        $request->validate([
            'plan_id' => 'required|exists:plans,id',
        ]);

        $user = auth()->user();
        $plan = Plan::findOrFail($request->plan_id);

        // Save subscription to DB (no Stripe)
        Subscription::updateOrCreate(
            ['user_id' => $user->id],
            [
                'plan_id' => $plan->id,
                'status'  => 'active',
                'paid_at' => now(),
            ]
        );

        session()->forget('plan_id');

        return redirect('/dashboard')
            ->with('success', '🎉 Your 7-day free trial has started!');
    }

    // ── Success ───────────────────────────────────────────────────
    public function success()
    {
        return redirect('/dashboard')
            ->with('success', '🎉 Payment successful!');
    }
}