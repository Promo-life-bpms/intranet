<?php

namespace App\Models\Soporte;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    use HasFactory;
    public $table = "soporte_status";
    protected $fillable = [
        'name',
        'slug',
    ];
}
