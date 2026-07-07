<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
   protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'company_name',
        'profile_picture',
        
        'industry',
        'domain',
        'phone',
        'location',
        'bio',
        'business_type',
    ];
      public function user()
    {
        return $this->belongsTo(User::class);
    }
}


