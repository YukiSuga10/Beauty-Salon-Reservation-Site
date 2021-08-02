<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StylistReview extends Model
{
    public function reserve(){
        return $this->belongsTo('App\Reserve');
    }
    
    public function stylist(){
        return $this->belongsTo('App\Stylist');
    }
}
