<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $fillable = ['admin_id','cut','color','perm','cut・color','cut・perm'];
    
    public function reserves(){
        return $this->hasMany('App\Reserve');
    }
    
    public function views(){
        return $this->hasMany('App\StylistReview');
    }
    
    public function admin(){
        return $this->belongsTo('App\Admin');
    }
}
