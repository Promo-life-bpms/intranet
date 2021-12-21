<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vacations extends Model
{
    use HasFactory;

    public $table = "vacations_availables";

    protected $fillable = [
        'id',
        'days_availables',
        'expiration'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'users_id');
    }

}
