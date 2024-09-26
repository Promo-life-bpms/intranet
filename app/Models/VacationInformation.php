<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VacationInformation extends Model
{
    use HasFactory;

    public $table = 'vacation_information';

    protected $fillable =
    [
        'total_days',
        'id_vacations_availables',
        'id_vacation_request'
    ];

    public function VacationRequest(){
        return $this->belongsTo(VacationRequest::class, 'id_vacation_request');
    }
    public function VacationAvailables(){
        return $this->belongsTo(Vacations::class, 'id_vacations_availables');
    }

}
