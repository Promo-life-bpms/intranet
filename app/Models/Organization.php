<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Organization extends Model
{
    use HasFactory;
    
    public $table = "companies";

    protected $fillable = [
        'id',
        'name_company',
        'description_company'
    ];



}
