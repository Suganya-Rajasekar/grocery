<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Referralsettings extends Model
{
    use HasFactory;
    
    public $table = "referral_settings";

    protected $fillable = ['referral_user_credit_amount','referral_user_orders_count','referer_user_credit_amount','referral_share_description'];
}
