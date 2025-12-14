<?php

namespace App\Models;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Friendship extends Model
{
    protected $fillable = [
        'user_id',
        'friend_id',
        'status'
    ];

    public function friend()
    {
        return $this->belongsTo(User::class, 'friend_id');
    }
}
