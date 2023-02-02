<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Themes extends Model
{
    use HasFactory;

    protected $table = 'homeevent_themes';
    
    protected $fillable = ['name','amount','images','status'];

    protected $appends  = ['theme_images']; 

    public function getThemeImagesAttribute()
    {
       $res_img = array(); 
       if($this->images) {
           $images = explode(',',$this->images);
           foreach($images as $key => $value) {
                $res_img[] =  asset('/storage/app/public/themes/'.$value);
           }   
       }
       return $res_img;
    }

}
