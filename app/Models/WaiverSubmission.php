<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WaiverSubmission extends Model
{
    protected $fillable = [
        'waiver_id',
        'sent_by',
        'client_id',
        'token',
        'responses',
        'signature',
        'status',
    ];

   protected $casts = [
    'responses' => 'array',
    
];

public function client()
{
    return $this->belongsTo(Client::class, 'client_id');
}

public function waiver()
{
    return $this->belongsTo(Waiver::class, 'waiver_id');
}
}