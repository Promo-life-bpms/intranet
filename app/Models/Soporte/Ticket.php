<?php

namespace App\Models\Soporte;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    public $table = "soporte_tickets";
    protected $fillable  = [
        'name',
        'category_id',
        'create',
        'data',
        'priority',
        'user_id',
        'status_id',
        'support_id'
    ];

    public function category()
    {
        return $this->belongsTo(Categoria::class, 'category_id');
    }

    public function status()
    {
        return $this->belongsTo(Status::class, 'status_id');
    }
    public function historial()
    {
        return $this->hasMany(Historial::class, 'ticket_id');
    }

    public function mensajes()
    {
        return $this->hasMany(Mensaje::class, 'ticket_id');
    }

    public function solution()
    {
        return $this->hasMany(Solucion::class, 'ticket_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function score()
    {
        return $this->hasOne(encuesta::class,'ticket_id');
    }

}
