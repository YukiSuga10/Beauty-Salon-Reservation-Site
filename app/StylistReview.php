<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StylistReview extends Model
{
    public function user(){
        return $this->belongsTo('App\User');
    }
    public function stylist(){
        return $this->belongsTo('App\Stylist');
    }
    public function menu(){
        return $this->belongsTo('App/Menu');
    }
    
    public function admin(){
        return $this->belongsTo('App\Admin');
    }
}
