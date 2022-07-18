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
        'start',
        'end',
        'reason',
        'reveal_id',
        'direct_manager_id',
        'direct_manager_status',
        'human_resources_status',
        'employee_id',
        'visible'
    ];


    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function manager()
    {
        return $this->belongsTo(Employee::class, 'direct_manager_id');
    }

    public function requestdays()
    {
        return $this->hasMany(RequestCalendar::class, 'requests_id');
    }

    public function requestrejected()
    {
        return $this->hasMany(RequestRejected::class, 'requests_id');
    }

    public function vacations()
    {
        return $this->belongsTo(Vacations::class, 'employee_id');
    }

    public function reveal()
    {
        return $this->belongsTo(User::class, 'reveal_id');
    }
}
