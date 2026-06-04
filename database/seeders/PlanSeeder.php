<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Plan;

class PlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Plan::create([
    'name' => 'Basic',
    'price' => 0
]);

Plan::create([
    'name' => 'Pro',
    'price' => 10
]);
    }
}
