<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StylistReview extends Model
{
    public function users(){
        return $this->belongsTo('App\User');
    }
    public function stylists(){
        return $this->belongsTo('App\Stylist');
    }
    public function menus(){
        return $this->belongsTo('App/Menu');
    }
}
