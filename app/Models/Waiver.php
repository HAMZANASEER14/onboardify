<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Waiver extends Model
{
  protected $fillable = [
     'user_id',        
        'title',          
    'fields',  // ✅ add this
    'status', 'slug', 'require_signature'
];
protected $casts = [
    'fields' => 'array', 
    ];

    // One waiver belongs to one user
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
