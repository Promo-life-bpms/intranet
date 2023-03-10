<?php

namespace App\Models\Soporte;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mensaje extends Model
{
    use HasFactory;
    public $table = "soporte_mensaje";
    protected $fillable = [
        'ticket_id',
        'message',
        'user_id',



    ];
}
