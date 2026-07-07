<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    protected $fillable = ['name', 'invite_code', 'owner_id'];

    // The Admin who owns this company
    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    // All users (employees/clients) in this company
    public function members()
    {
        return $this->hasMany(User::class, 'team_id');
    }

    // All tasks assigned within this company
    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    // All salary slips for this company
    public function salarySlips()
    {
        return $this->hasMany(SalarySlip::class);
    }
}