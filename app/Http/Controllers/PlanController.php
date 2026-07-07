<?php

namespace App\Http\Controllers;

use App\Http\Requests\SelectPlanRequest;
use App\Repositories\Contracts\PaymentRepositoryInterface;

class PlanController extends Controller
{
    public function __construct(private PaymentRepositoryInterface $payments) {}

    public function index()
    {
        // ✅ If already subscribed, skip to dashboard
        $subscription = $this->payments->getActiveSubscription(auth()->id());

        if ($subscription) {
            return redirect()->route('admin.dashboard');
        }

        $plans = $this->payments->getAllPlans();
        return view('plans.index', compact('plans'));
    }

    public function select(SelectPlanRequest $request)
    {
        $this->payments->selectPlan($request->plan_id);

        return redirect('/payment');
    }
}