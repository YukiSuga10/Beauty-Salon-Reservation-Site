<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Reserve extends Model
{
    protected $fillable = [
    'date'
    ];
    
    
    
    public function stylist(){
        return $this->belongsTo('App\Stylist');
    }
    
    public function review(){
        return $this->hasOne('App\StylistReview');
    }
    
    public function admin(){
        return $this->belongsTo('App\Admin');
    }
    
    public function user(){
        return $this->belongsTo('App\User');
    }
    
    public function menus(){
        return $this->belongsTo('App\Menu');
    }
    
    public function getPaginateBylimit(int $limit_count) 
    {
        return $this->paginate($limit_count);
    }
    
}
