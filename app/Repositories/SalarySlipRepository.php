<?php

namespace App\Repositories;

use App\Models\SalarySlip;
use App\Models\User;
use App\Repositories\Contracts\SalarySlipRepositoryInterface;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;

class SalarySlipRepository implements SalarySlipRepositoryInterface
{
    public function getAllForTeam(int $teamId): Collection
    {
        return SalarySlip::where('team_id', $teamId)
            ->with('user')
            ->latest()
            ->get();
    }

    public function getTeamEmployees(int $teamId): Collection
    {
        return User::where('team_id', $teamId)
            ->where('role', 'employee')
            ->get();
    }

    public function storeFile(UploadedFile $file): string
    {
        return $file->store('salary_slips', 'public');
    }

    public function create(array $data): SalarySlip
    {
        return SalarySlip::create($data);
    }

    public function fileExists(SalarySlip $salarySlip): bool
    {
        return Storage::disk('public')->exists($salarySlip->file_path);
    }

    public function delete(SalarySlip $salarySlip): void
    {
        Storage::disk('public')->delete($salarySlip->file_path);
        $salarySlip->delete();
    }
}