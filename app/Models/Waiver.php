<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Waiver extends Model
{
  protected $fillable = [
     'user_id',        
        'title', 
        'pdf_document',         
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
    public function sends()
    {
        return $this->hasMany(WaiverSend::class);
    }

    public function submissions()
    {
        return $this->hasMany(WaiverSubmission::class);
    }
}
