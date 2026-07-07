<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Task extends Model
{
    protected $fillable = [
        'team_id',
        'assigned_to',
        'assigned_by',
        'title',
        'description',
        'status',
        'due_date'
    ];

    protected $casts = [
        'due_date' => 'date',
    ];

    // The company this task belongs to
    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    // The employee who needs to do the task
    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    // The admin who created the task
    public function assignedBy()
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }
}