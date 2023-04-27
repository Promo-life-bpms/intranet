<?php

namespace App\Models;

use App\Http\Controllers\SeeMore;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Laratrust\Traits\LaratrustUserTrait;

class User extends Authenticatable
{
    use LaratrustUserTrait;
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'lastname',
        'image',
        'email',
        'password',
        'status',
        'last_login'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::created(function ($user) {
            $user->employee()->create();
        });

        /*  static::created(function ($user) {
            $user->contact()->create();
        });

        static::created(function ($user) {
            $user->vacation()->create();
        });*/
    }

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

    public function vacationsAvailables()
    {
        return $this->hasMany(Vacations::class, 'users_id')->where('period', '<>', 3);
    }

    public function directory()
    {
        return $this->hasMany(Directory::class, 'user_id');
    }
    public function publications()
    {
        return $this->hasMany(Publications::class, 'user_id');
    }

    public function meGusta()
    {
        return $this->belongsToMany(Publications::class, 'likes', 'user_id', 'publication_id');
    }
    public function messages()
    {
        return $this->hasMany(Message::class, 'transmitter_id');
    }

    // Dias seleccionados que no estan ligados a un request
    public function daysSelected()
    {
        return $this->hasMany(RequestCalendar::class, 'users_id')->where('requests_id', null);
    }

    public function requests()
    {
        return $this->hasMany(Request::class);
    }
       
    public function seemores()
    {
        return $this->hasMany(SeeMore::class, 'comments');
    }
}