<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VacationDays extends Model
{
    use HasFactory;

    public $table = 'vacation_days';

    protected $fillable = [
        'day',
        'start',
        'end',
        'vacation_request_id',
        'status'
    ];

    public function vacationrequest()
    {
        return $this->belongsTo(VacationRequest::class, 'vacation_request_id');
    }



}
