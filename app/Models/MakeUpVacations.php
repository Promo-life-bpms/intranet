<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MakeUpVacations extends Model
{
    use HasFactory;

    public $table = 'make_up_vacations';

    protected $fillable = [
        'user_id',
        'num_days',
        'subtract_days',
        'description'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
