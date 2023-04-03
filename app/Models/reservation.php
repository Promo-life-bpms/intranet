<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class reservation extends Model
{
    use HasFactory;
    public $table='reservations';
    
    public function users()
    {
        return $this->hasOne(User::class, 'id_usuario');
    }

    public function boordroms()
    {
        return $this->hasOne(boardroom::class,'id_sala');
    }

    static $rules=[
        'number_of_people'=>'required',
        'material'=>'required',
        'chair_loan'=>'required',
        'description'=>'required',
    ];

    protected $fillable=['number_of_people', 'material','chair_loan','description'];
}
