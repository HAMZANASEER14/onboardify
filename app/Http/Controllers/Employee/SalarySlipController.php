<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\SalarySlip;
use Illuminate\Support\Facades\Storage;

class SalarySlipController extends Controller
{
    public function index()
    {
        $salarySlips = SalarySlip::where('user_id', auth()->id())
            ->latest()
            ->get();

        return view('employee.salary.index', compact('salarySlips'));
    }

    public function download(SalarySlip $salarySlip)
    {
        // Employee can only download their own slips
        abort_if($salarySlip->user_id !== auth()->id(), 403);

        return Storage::disk('public')->download($salarySlip->file_path);
    }
}