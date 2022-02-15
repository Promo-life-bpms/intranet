<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Directory
 *
 * @property $id
 * @property $user_id
 * @property $type
 * @property $data
 * @property $company
 * @property $created_at
 * @property $updated_at
 *
 * @property User $user
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Directory extends Model
{

    static $rules = [];

    protected $perPage = 20;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['user_id', 'type', 'data', 'company'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
    public function companyName()
    {
        return $this->belongsTo(Company::class, 'company');
    }
}
