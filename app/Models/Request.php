<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Request extends Model
{
    use HasFactory;

    public $table = "request";


    protected $fillable = [
        'id',
        'nombre_soli',
        'fecha_soli',
        'tipo_soli',
        'dias_soli',
        'especificacion_soli',
        'motivo_soli',
        'status',
        'id_empleado'
    ];
}
