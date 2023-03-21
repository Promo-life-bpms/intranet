<?php

namespace App\Models\Soporte;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{

    use HasFactory;
    public $table = "soporte_categorias";
    protected $fillable = [
        'name',
        'status',
        'slug',
    ];

}
