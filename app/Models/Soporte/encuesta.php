<?php

namespace App\Models\Soporte;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class encuesta extends Model
{
    use HasFactory;
    public $table="encuestas";
    protected $fillable=[
        'ticket_id',
        'comments',
        'score'
    ];
}
