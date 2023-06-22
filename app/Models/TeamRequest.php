<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeamRequest extends Model
{
    use HasFactory;

    public $table='request_for_systems_and_communications_services';

    protected $fillable = ['odoo_users', 'work_profile_in_odoo'];

    public function user()
    {
        return $this->belongsTo(User::class, 'name');
    }

}
