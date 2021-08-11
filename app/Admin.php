<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Admin extends Authenticatable
{
    use Notifiable;
    
    protected $primaryKey = 'id';

    protected $guard = 'admin';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password','gender',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    
    
    public function reserves(){
        return $this->hasMany('App\Reserve');
    }
    
    public function stylists(){
        return $this->hasMany('App\Stylist');
    }
    
    public function menus(){
        return $this->hasMany('App\Menu');
    }
    
    public function reviews(){
        return $this->hasMany('App\StylistReview');
    }
    
    public function salon_images(){
        return $this->hasMany('App\SalonImage');
    }
    
}
