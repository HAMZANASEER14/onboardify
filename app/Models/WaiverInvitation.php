<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WaiverInvitation extends Model
{
     protected $fillable = [
        'client_id', 'waiver_id', 'user_id', 'token', 'signed_at'
    ];

    public function client() { return $this->belongsTo(Client::class); }
    public function waiver() { return $this->belongsTo(Waiver::class); }
}
