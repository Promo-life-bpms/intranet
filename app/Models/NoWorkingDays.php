<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NoWorkingDays extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'date',
        'reason'
    ];

    public $timestamps = false;

    public function company(){
        return $this->belongsTo(Company::class,'companies_id');
    }


}
