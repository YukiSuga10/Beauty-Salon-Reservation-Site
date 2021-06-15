<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    public function reserves(){
        return $this->hasMany('App\Reserve','menu');
    }
}
