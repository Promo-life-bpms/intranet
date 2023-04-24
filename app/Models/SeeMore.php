<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User;

class SeeMore extends Model
{
    use HasFactory;

    public $table='request_team';

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

}
