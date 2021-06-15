<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Reserve extends Model
{
     public function stylists(){
        return $this->belongsTo('App\Stylist');
    }
    
    public function users(){
        return $this->belongsTo('App\User');
    }
    
    public function menus(){
        return $this->belongsTo('App\Menu');
    }
}
