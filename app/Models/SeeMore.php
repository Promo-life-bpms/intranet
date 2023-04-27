<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SeeMore extends Model
{
    use HasFactory;

    public $table='request_team';
    protected $fillable = ['user_id','category','description','status','comments'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }


}
