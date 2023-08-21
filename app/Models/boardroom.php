<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class boardroom extends Model
{
    use HasFactory;
    public $table='boardrooms';

    static $rules=[
        'name'=>'required',
        'location'=>'required',
        'capacitance'=>'required',
        'description'=>'required',
    ];

    protected $fillable=['name', 'location','capacitance','description'];
}
