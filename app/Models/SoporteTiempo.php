<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SoporteTiempo extends Model
{
    use HasFactory;

    public $table="soporte_tiempos";
    protected $fillable=[
        'priority',
        'time',
    ];

}
