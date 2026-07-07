<?php

namespace App\Repositories;

use App\Models\Plan;
use App\Models\Subscription;
use App\Repositories\Contracts\PaymentRepositoryInterface;
use Stripe\PaymentIntent;
use Stripe\Stripe;

use Illuminate\Database\Eloquent\Collection;
class PaymentRepository implements PaymentRepositoryInterface
{
    public function findPlanForSession(): ?Plan
    {
        if (!session()->has('plan_id')) {
            return null;
        }

        $plan = Plan::find(session('plan_id'));
        if (!$plan) {
            return null;
        }

        if (!$plan->annual_price) {
            $plan->annual_price = $plan->price * 10;
        }

        return $plan;
    }

    public function createPaymentIntent(Plan $plan, int $userId): array
    {
        $amount = $plan->price * 100; // SGD cents

        if ($amount === 0) {
            return ['client_secret' => null, 'free' => true];
        }

        Stripe::setApiKey(config('services.stripe.secret'));

        $paymentIntent = PaymentIntent::create([
            'amount'               => $amount,
            'currency'             => 'sgd', // ✅ Fixed
            'payment_method_types' => ['card'],
            'metadata'             => [
                'user_id' => $userId,
                'plan_id' => $plan->id,
            ],
        ]);

        return ['client_secret' => $paymentIntent->client_secret];
    }

    public function retrievePaymentIntent(string $paymentIntentId): PaymentIntent
    {
        Stripe::setApiKey(config('services.stripe.secret'));

        return PaymentIntent::retrieve($paymentIntentId);
    }

    public function activateSubscription(int $userId, int $planId, string $paymentIntentId): Subscription
    {
        $subscription = Subscription::updateOrCreate(
            ['user_id' => $userId],
            [
                'plan_id'    => $planId,
                'status'     => 'active',
                'payment_id' => $paymentIntentId,
                'paid_at'    => now(),
            ]
        );

        session()->forget('plan_id');

        return $subscription;
    }
    public function getActiveSubscription(int $userId): ?Subscription
{
    return Subscription::where('user_id', $userId)
                        ->where('status', 'active')
                        ->first();
}

public function getAllPlans(): Collection
{
    return Plan::all();
}

public function selectPlan(int $planId): void
{
    session(['plan_id' => $planId]);
}
}