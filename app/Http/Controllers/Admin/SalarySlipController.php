<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSalarySlipRequest;
use App\Models\SalarySlip;
use App\Repositories\Contracts\SalarySlipRepositoryInterface;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class SalarySlipController extends Controller
{
    public function __construct(
        protected SalarySlipRepositoryInterface $salarySlips
    ) {
    }

    public function index(): View
    {
        $user = auth()->user();

        $salarySlips = $this->salarySlips->getAllForTeam($user->team_id);

        return view('admin.salary.index', compact('salarySlips'));
    }

    public function create(): View
    {
        $user = auth()->user();

        $employees = $this->salarySlips->getTeamEmployees($user->team_id);

        return view('admin.salary.create', compact('employees'));
    }

    public function store(StoreSalarySlipRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $user      = auth()->user();

        $filePath = $this->salarySlips->storeFile($request->file('file'));

        $this->salarySlips->create([
            'team_id'   => $user->team_id,
            'user_id'   => $validated['user_id'],
            'month'     => $validated['month'],
            'file_path' => $filePath,
            'emailed'   => false,
        ]);

        return redirect()->route('admin.salary.index')
            ->with('success', 'Salary slip uploaded successfully!');
    }

    /**
     * Admin download — any slip within their team.
     */
    public function download(SalarySlip $salarySlip)
    {
        if ($salarySlip->team_id !== auth()->user()->team_id) {
            abort(403);
        }

        if (! $this->salarySlips->fileExists($salarySlip)) {
            abort(404, 'File not found.');
        }

        $fileName = 'salary-slip-' . $salarySlip->month . '.pdf';

        return Storage::disk('public')
            ->download($salarySlip->file_path, $fileName);
    }

    /**
     * Employee download — only their own slip.
     * Route: GET /employee/salary/{salarySlip}/download
     * Name:  salary.download
     */
    public function employeeDownload(SalarySlip $salarySlip)
    {
        if ($salarySlip->user_id !== auth()->id()) {
            abort(403);
        }

        if (! $this->salarySlips->fileExists($salarySlip)) {
            abort(404, 'File not found.');
        }

        $fileName = 'salary-slip-' . $salarySlip->month . '.pdf';

        return Storage::disk('public')
            ->download($salarySlip->file_path, $fileName);
    }

    public function destroy(SalarySlip $salarySlip): RedirectResponse
    {
        if ($salarySlip->team_id !== auth()->user()->team_id) {
            abort(403);
        }

        $this->salarySlips->delete($salarySlip);

        return redirect()->route('admin.salary.index')
            ->with('success', 'Salary slip deleted successfully!');
    }
}