<?php

namespace App\Models\Soporte;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Historial extends Model
{
    use HasFactory;
    public $table = "soporte_historial";
    protected $fillable = [
        'ticket_id',
        'user_id',
        'type',
        'data',
    ];

    
}
