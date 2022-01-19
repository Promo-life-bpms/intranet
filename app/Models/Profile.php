<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    use HasFactory;

    public function employee()
    {
        return $this->hasOne(Employee::class);
    }

    public function contact()
    {
        return $this->hasOne(Contact::class);
    }

    public function vacation()
    {
        return $this->hasOne(Vacations::class, 'users_id');
    }
}
