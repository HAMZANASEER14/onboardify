<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class WaiverSend extends Model
{
protected $fillable = [
    'waiver_id',
    'client_id',
    'client_name',
    'client_email',
    'sent_by',
    'token',
    'status',
    'signed_at',
];

    protected $casts = [
        'signed_at' => 'datetime',
    ];

    public function waiver()
    {
        return $this->belongsTo(Waiver::class);
    }

   public function client()
{
    return $this->belongsTo(Client::class, 'client_id');
}
}