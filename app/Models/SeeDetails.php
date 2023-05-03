<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SeeDetails extends Model
{
    use HasFactory;

    public $table='request_team';

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
