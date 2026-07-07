<?php

namespace App\Repositories\Contracts;

use App\Models\Waiver;

interface TemplateRepositoryInterface
{
    public function all(): array;

    public function find(string $templateId): ?array;

    public function createWaiverFromTemplate(int $userId, array $template): Waiver;
}