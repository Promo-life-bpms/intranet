<?php

namespace App\Models\Soporte;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Solucion extends Model
{
    use HasFactory;
    public $table = "soporte_solucion";
    protected $fillable =[
        'description',
        'user_id',
        'ticket_id'
    ];
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    //  public function solution()
    //  {
    //      return $this->belongsTo(Ticket::class,'ticket_id');
    //  }

}
