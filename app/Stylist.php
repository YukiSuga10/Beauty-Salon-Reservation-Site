<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Stylist extends Model
{
    protected $table = 'stylists';
    
    public function users(){
        return $this->belongsToMany('App\User');
    }
    
    public function reserve(){
        return $this->belongsTo('App\Reserve');
    }
    
    public function admin(){
        return $this->belongsTo('App\Admin');
    }
    
    public function reviews(){
        return $this->hasMany('App\StylistReview');
    }
    
    public function file_images(){
        return $this->hasOne('App\file_Image');
    }
    
    
}
