<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $fillable = [
        'user_id', 'name', 'email', 'phone', 'status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}