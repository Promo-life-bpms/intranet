<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NoWorkingDays extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'reason'
    ];

    public $timestamps = false;
}
