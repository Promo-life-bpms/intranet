<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;
    public $table = "employee";

    protected $fillable = [
        'id',
        'nombre',
        'paterno',
        'materno',
        'fecha_cumple',
        'fecha_ingreso',
        'status',
        'id_contacto',
        'id_user'
    ];
}
