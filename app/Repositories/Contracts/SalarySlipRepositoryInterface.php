<?php

namespace App\Repositories\Contracts;

use App\Models\SalarySlip;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;

interface SalarySlipRepositoryInterface
{
    /**
     * Get all salary slips for a given team, with user relation loaded.
     */
    public function getAllForTeam(int $teamId): Collection;

    /**
     * Get all employees belonging to a team.
     */
    public function getTeamEmployees(int $teamId): Collection;

    /**
     * Store the uploaded PDF file on disk and return its path.
     */
    public function storeFile(UploadedFile $file): string;

    /**
     * Create a new salary slip record.
     */
    public function create(array $data): SalarySlip;

    /**
     * Check whether the file for a salary slip exists on disk.
     */
    public function fileExists(SalarySlip $salarySlip): bool;

    /**
     * Delete a salary slip and its underlying file.
     */
    public function delete(SalarySlip $salarySlip): void;
}