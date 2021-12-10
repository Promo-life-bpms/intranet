<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'birthday_date',
        'date_admission',
        'status',
        'jefe_directo_id',
        'position_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function yourRequests()
    {
        return $this->hasMany(Request::class);
    }

    public function companies()
    {
        return $this->belongsToMany(Company::class);
    }

    public function position()
    {
        return $this->belongsTo(Position::class);
    }

    public function communiques()
    {
        return $this->hasMany(Communique::class, 'creator_id');
    }

    public function jefeDirecto()
    {
        return $this->hasOne(Employee::class, 'id', 'jefe_directo_id');
    }

    public function subordinados()
    {
        return $this->hasMany(Employee::class, 'jefe_directo_id', 'id');
    }
}
