<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;
    public $table = "companies";

    protected $fillable = [
        'id',
        'name_company',
        'description_company'
    ];

    public function employees()
    {
        return $this->belongsToMany(Employee::class, 'company_employee', 'company_id', 'employee_id');
    }
}
