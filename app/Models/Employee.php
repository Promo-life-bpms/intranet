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
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
