<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Plan;

class PlanController extends Controller
{
    public function index()
{
    $plans = Plan::all();
    return view('plans.index', compact('plans'));
}
public function select(Request $request)
{
    $request->validate([
        'plan_id' => 'required|exists:plans,id',
    ]);

    session(['plan_id' => $request->plan_id]);

    return redirect('/payment');
}
}
