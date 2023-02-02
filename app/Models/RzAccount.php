<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class rz_accounts
 * @package App\Models
 * @version October 9, 2020, 7:06 am UTC
 *
 * @property string|\Carbon\Carbon $created_dt
 */
class RzAccount extends Model
{
    public $table = 'rz_accounts';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    public $fillable = [
        'contact', 'fund', 'name', 'account_number', 'ifsc_code', 'contact_status', 'fund_status', 'status','chef'
    ];

    public function scopeApprovedcontact($query)
    {
        $query->where('contact_status',1);
    }

    public function scopeApprovedfund($query)
    {
        $query->where('fund_status',1);
    }
 }
