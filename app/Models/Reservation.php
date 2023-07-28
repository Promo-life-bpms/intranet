<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;
    public $table='reservations';
    public $timestamps=false;
    
    protected $fillable=['title','start','end','number_of_people', 'material','chair_loan','description'];

    public function users()
    {
        return $this->belongsTo(User::class, 'id_usuario');
    }

    public function boordroms()
    {
        return $this->belongsTo(boardroom::class,'id_sala');
    }
}
