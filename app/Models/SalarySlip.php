<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalarySlip extends Model
{
    protected $fillable = [
        'team_id',
        'user_id',
        'month',
        'file_path',
        'emailed'
    ];

    protected $casts = [
        'emailed' => 'boolean',
    ];

    // The company
    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    // The employee who receives this slip
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}