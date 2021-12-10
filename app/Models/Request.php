<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Request extends Model
{
    use HasFactory;

    public $table = "requests";

    protected $fillable = [
        'type_request',
        'payment',
        'absence',
        'admission',
        'reason',
        'direct_manager_id',
        'direct_manager_status',
        'human_resources_status',
        'employee_id'
    ];


    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function manager()
    {
        return $this->belongsTo(Employee::class);
    }
}
