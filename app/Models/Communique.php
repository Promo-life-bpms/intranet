<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Communique extends Model
{
    use HasFactory;

    protected $fillable = [
        'description', 'images', 'title', 'files'
    ];

    public function employeesAttachment()
    {
        return $this->belongsToMany(Employee::class, 'communique_employee', 'communique_id', 'employee_id');
    }
}
