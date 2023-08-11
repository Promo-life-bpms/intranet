<?php

namespace App\Models\Soporte;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mensaje extends Model
{
    use HasFactory;
    public $table = "soporte_mensajes";
    protected $fillable = [
        'ticket_id',
        'mensaje',
        'user_id',
    ];

    public function usuarios(){
        return $this->belongsTo (User::class,'user_id');
    }
}
