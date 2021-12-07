<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'nombre',
        'paterno',
        'materno',
        'fecha_cumple',
        'fecha_ingreso',
        'status',
        'jefe_directo_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function companies()
    {
        return $this->belongsToMany(Company::class);
    }

    public function positions()
    {
        return $this->belongsToMany(Position::class);
    }

    public function communiques()
    {
        return $this->hasMany(Communique::class, 'creator_id');
    }

    public function jefeDirecto()
    {
        return $this->hasOne(Employee::class, 'id', 'jefe_directo_id',);
    }

    public function subordinados()
    {
        return $this->hasMany(Employee::class, 'jefe_directo_id', 'id');
    }
}
