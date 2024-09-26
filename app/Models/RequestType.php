<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequestType extends Model
{
    use HasFactory;

    public $table = 'request_types';

    protected $fillable = [
        'type',
        'description',
        'max_hours_peer_day',
        'uses_peer_mont',
        'continuos_days',
        'max_continuos_uses',
        'min_month_time',
        'comprobation',
        'status'
    ];
}
