<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Relations\HasOne;
use App\Models\Profile;
use Laravel\Cashier\Billable;
use App\Models\Group;
use App\Notifications\VerifyEmailNotification;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, Billable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'team_id',
        'company_id',   
    'joined_at',
    'company_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'joined_at'         => 'datetime',
            'password' => 'hashed',
        ];
    }
    // protected $casts = [
    //     'password' => 'hashed',
    // ];

public function profile(): HasOne
{
    return $this->hasOne(Profile::class);
}
public function subscription()
{
    return $this->hasOne(\App\Models\Subscription::class);
}
public function groups()
{
    return $this->belongsToMany(Group::class, 'group_members')
                ->withPivot('role', 'joined_at')
                ->withTimestamps();
}
public function sendEmailVerificationNotification(): void
{
    $this->notify(new VerifyEmailNotification());
}
// The team/company the user belongs to
public function team()
{
    return $this->belongsTo(Team::class);
}

// Tasks assigned TO this user (for employees)
public function assignedTasks()
{
    return $this->hasMany(Task::class, 'assigned_to');
}

// Tasks created BY this user (for admins)
public function createdTasks()
{
    return $this->hasMany(Task::class, 'assigned_by');
}

// Salary slips for this user
public function salarySlips()
{
    return $this->hasMany(SalarySlip::class);
}
}