<?php

namespace App\Models\Soporte;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    public $table = "soporte_tickets";
    protected $fillable  = [
        'name',
        'category_id',
        'image',
        'create',
        'data',
        'status_id'
    ];

    public function category()
    {
        return $this->belongsTo(Categorias::class);
    }
    public function status()
    {
        return $this->belongsTo(Status::class);
    }
    public function historial()
    {
        return $this->hasMany(Historial::class);
    }
    public function mensajes()
    {
        return $this->hasMany(Mensajes::class);
    }
}
