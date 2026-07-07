<?php

namespace App\Repositories\Contracts;

use App\Models\Plan;
use App\Models\Subscription;
use Stripe\PaymentIntent;

interface PaymentRepositoryInterface
{
    public function findPlanForSession(): ?Plan;
    public function createPaymentIntent(Plan $plan, int $userId): array;
    public function retrievePaymentIntent(string $paymentIntentId): PaymentIntent;
    public function activateSubscription(int $userId, int $planId, string $paymentIntentId): Subscription;
    public function getActiveSubscription(int $userId): ?\App\Models\Subscription;
public function getAllPlans(): \Illuminate\Database\Eloquent\Collection;
public function selectPlan(int $planId): void;
}