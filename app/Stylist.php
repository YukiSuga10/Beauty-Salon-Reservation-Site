<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Stylist extends Model
{
    protected $table = 'stylists';
    
    public function reserves(){
        return $this->belongsToMany('App\User');
    }
}
