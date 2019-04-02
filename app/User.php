<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Cashier\Billable;

/**
 * App\User
 *
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User query()
 * @mixin \Eloquent
 */
class User extends Authenticatable
{
    use Notifiable , Billable;

    //funcion para el slug en insertar datos
    protected static function boot (){
        parent::boot();
        static::creating(function (User $user){
            //que el usuario no se inserte por consola
            if(! \App::runningInConsole()){
                $user->slug = str_slug($user->name . " " . $user->last_name , "-");
            }
        });

    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public static function navigation (){
        //devolvemos si es esta autentificado admin o estudent o teacher => user es un objeto de la clase
        return auth()->check() ? auth()->user()->role->name : 'guest';
    }

    public function role (){
        return $this->belongsto(Role::Class);
    }

    public function student (){
        return $this->hasOne(Student::class);
    }

    public function teacher (){
        return $this->hasOne(Teacher::class);
    }

    public function socialAccount (){
        return $this->hasOne(UserSocialAccount::class);
    }
}
