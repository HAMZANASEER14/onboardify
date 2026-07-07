<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invite extends Model
{
    protected $fillable = [
        'admin_id',
        'email',
         'role',  
        'status',
        'failure_reason',
        'source',
        'joined_at',
    ];
    protected $casts = [
        'joined_at' => 'datetime',
    ];
    public function admin()
    {
        return $this->belongsTo(\App\Models\User::class, 'admin_id');
    }
}
