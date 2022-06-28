<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Provider
 *
 * @property $id
 * @property $name
 * @property $service
 * @property $type
 * @property $name_contact
 * @property $position
 * @property $tel_office
 * @property $tel_cel
 * @property $email
 * @property $address
 * @property $web_page
 * @property $created_at
 * @property $updated_at
 *
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Provider extends Model
{

    static $rules = [
        'name' => 'required',
        'service' => 'required',
        'type' => 'required',
        'name_contact' => 'required',
        'position' => 'required',
    ];

    protected $perPage = 20;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'service', 'type', 'name_contact', 'position', 'tel_office', 'tel_cel', 'email', 'address', 'web_page'];
}
