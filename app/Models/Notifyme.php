<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Chefs;

class Notifyme extends Model
{
    use HasFactory;
    protected $table = "notify_me"; 
    public $fillable = ['user_id','vendor_id','status'];

    public function user()
    {
        return $this->belongsTo(User::class,'user_id','id');
    }

    public function chef()
    {
        return $this->belongsTo(Chefs::class,'vendor_id','id');
    }

}
