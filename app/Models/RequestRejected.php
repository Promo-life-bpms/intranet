<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequestRejected extends Model
{
    use HasFactory;

    public $table = "request_rejected";

    protected $fillable = [
        'title', 'start', 'end', 'users_id','requests_id'
    ];


}
