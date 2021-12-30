<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Events extends Model
{
    use HasFactory;

    public $table = "events";
    protected $fillable = [
        'title', 'start', 'time','description','users_id'
    ];

    public function users()
    {
        return $this->belongsTo(User::class, 'users_id');
    }

}
