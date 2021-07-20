<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Reserve extends Model
{
    protected $fillable = [
    'date'
    ];
    
    public function stylists(){
        return $this->belongsTo('App\Stylist');
    }
    
    public function users(){
        return $this->belongsTo('App\User');
    }
    
    public function menus(){
        return $this->belongsTo('App\Menu');
    }
    
    public function getPaginateBylimit(int $limit_count = 10) 
    {
        return $this->orderBy('date', 'DESC')->paginate($limit_count);
    }
    
}
