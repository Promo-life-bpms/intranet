<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VacationRequest extends Model
{
    use HasFactory;

    public $table = 'vacation_requests';

    protected $fillable = [
        'user_id',
        'request_type_id',
        'details',
        'more_information',
        'reveal_id',
        'file',
        'commentary',
        'direct_manager_id',
        'direct_manager_status',
        'rh_status'
    ];

    public function users()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function typerequest()
    {
        return $this->belongsTo(RequestType::class, 'request_type_id');
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'direct_manager_id');
    }

}
