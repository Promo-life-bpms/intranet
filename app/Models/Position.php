<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Position extends Model
{
    use HasFactory;

    public $table = "positions";

    protected $fillable = [
        'name',
        'department_id'
    ];

    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }
    public function employees()
    {
        return $this->belongsToMany(Employee::class, 'employee_position', 'position_id', 'employee_id');
    }
    public function getEmployees()
    {
        return $this->hasMany(Employee::class);
    }
}
