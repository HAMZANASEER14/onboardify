<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
   protected $fillable = [
    'user_id',
    'business_type',
    'first_name',
    'last_name',
    'phone',
    'company_name',
    'industry',
    'domain',
    'location',
    'address',
    'bio',
    'profile_image',
];
}

