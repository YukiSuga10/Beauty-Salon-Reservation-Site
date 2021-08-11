<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SalonImage extends Model
{
    protected $fillable = ['admin_id', 'path'];
    
    public function admins(){
        return $this->belongsTo('App\Admin');
    }
}
