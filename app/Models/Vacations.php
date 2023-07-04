<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vacations extends Model
{
    use HasFactory;

    public $table = "vacations_availables";

    protected $fillable = [
        'days_availables',
        'dv',
        'period',
        'cutoff_date',
        'date_end',
        'date_start',
        'days_enjoyed',
        "users_id"
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'users_id');
    }
}
