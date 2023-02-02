<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WalletHistory extends Model
{
    use HasFactory;

    protected $table = 'wallet_history';

    protected $fillable = ['user_id','amount','type','notes','balance'];

    protected $appends = ['history_date'];

    public function order() 
    {
        return $this->hasOne('App\Models\Order','wallet_history_id','id');
    }

    public function getHistoryDateAttribute()
    {
        return date('d-m-Y',strtotime($this->created_at));
    }

    public function user()
    {
        return $this->hasOne('App\Models\User','id','user_id');
    }
}
