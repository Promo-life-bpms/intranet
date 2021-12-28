<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Manager extends Model
{
    use HasFactory;

    public $table = "manager";

    protected $fillable = [
        'id',
        'users_id',
        'department_id'
    ];



    public function user()
    {
        return $this->belongsTo(User::class, 'users_id');
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }


}
