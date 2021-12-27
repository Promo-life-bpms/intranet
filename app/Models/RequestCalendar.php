<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequestCalendar extends Model
{
    use HasFactory;

    public $table = "request_calendars";
    protected $fillable = [
        'title', 'start', 'end', 'users_id'
    ];
}
