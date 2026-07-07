<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreatePaymentIntentRequest;
use App\Http\Requests\ProcessPaymentRequest;
use App\Models\Plan;
use App\Repositories\Contracts\PaymentRepositoryInterface;

class PaymentController extends Controller
{
    public function __construct(private PaymentRepositoryInterface $payments) {}

    public function index()
    {
        $plan = $this->payments->findPlanForSession();

        if (!$plan) {
            return redirect('/plans');
        }

        return view('payment.index', compact('plan'));
    }

    // Step 1: Create PaymentIntent, return client_secret to JS
    public function createIntent(CreatePaymentIntentRequest $request)
    {
        $plan = Plan::findOrFail($request->plan_id);

        try {
            $result = $this->payments->createPaymentIntent($plan, auth()->id());

            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        }
    }

    public function processPayment(ProcessPaymentRequest $request)
    {
        try {
            $paymentIntent = $this->payments->retrievePaymentIntent($request->payment_intent_id);

            if ($paymentIntent->status !== 'succeeded') {
                return response()->json(['error' => 'Payment not confirmed by Stripe.'], 422);
            }

            // ── Verify this PaymentIntent actually belongs to this user and this plan ──
            if (
                ($paymentIntent->metadata->user_id ?? null) != auth()->id() ||
                ($paymentIntent->metadata->plan_id ?? null) != $request->plan_id
            ) {
                return response()->json(['error' => 'Payment does not match this user or plan.'], 422);
            }

            // ── Verify the amount charged matches the plan's actual price ──
            $plan = Plan::findOrFail($request->plan_id);
            if ($paymentIntent->amount !== (int) round($plan->price * 100)) {
                return response()->json(['error' => 'Payment amount mismatch.'], 422);
            }

            $this->payments->activateSubscription(auth()->id(), $request->plan_id, $request->payment_intent_id);

            return response()->json(['success' => true]);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        }
    }

    public function success()
    {
        return redirect('/dashboard')->with('success', '🎉 Payment successful!');
    }
}