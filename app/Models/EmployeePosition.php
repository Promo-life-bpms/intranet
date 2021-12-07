<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeePosition extends Model
{
    use HasFactory;

    public $table = "employee_position";


    protected $fillable = [
        'id',
        'employee_id',
        'position_id'
    ];

   

    public function employee()
    {
        return $this->belongsTo(Employee::class,'id');
    }

    public function position()
    {
        return $this->belongsTo(Position::class, 'id');
    }

}
