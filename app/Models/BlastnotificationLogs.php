<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\URL;
use App\Traits\Log;
use App\Models\User;
use App\Models\Chefs;

class BlastNotificationLogs extends Model
{
    use Log;
    public $table = 'blast_notification_logs';

    public $fillable = ['subject','message','users','chefs','status'];

    public function getuserAttribute()
    {
        if($this->users == 'all_users' || $this->users == 'none'){
            return $this->users;
        }
        $users = User::select('id','name')->whereIn('id',explode(',',$this->users))->get();
        foreach($users as $key => $value) {
            $users_name[] = $value->name;
        }
        return !empty($users_name) ? implode(',',$users_name) : '';
    }

    public function getchefAttribute()
    {
        if($this->chefs == 'all_chefs' || $this->chefs == 'none'){
            return $this->chefs;
        } 
        $chefs = Chefs::select('id','name','avatar')->whereIn('id',explode(',',$this->chefs))->get();
        foreach ($chefs as $key => $value) {
            $chefs_name[] = $value->name;
        }
        return !empty($chefs_name) ? implode(',',$chefs_name) : '';
    }

}
