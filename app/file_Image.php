<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class file_Image extends Model
{
    //
    protected $fillable = ['file_name'];
    
    
    protected $table = 'file_images';
}
