<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Request extends Model
{
    use HasFactory;

    public $table = "request";

    protected $fillable = [
        'type_request',
        'days_absence',
        'reason',
        'direct_manager_id',
        'direct_manager_status',
        'human_resources_status',
    ];
}
