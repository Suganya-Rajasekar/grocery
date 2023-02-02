<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use App\Models\Translate;
use App\Traits\Log;

/**
 * Class Offer
 * @package App\Models
 * @version October 9, 2020, 7:06 am UTC
 *
 * @property string|\Carbon\Carbon $created_dt
 */
class SettingsApiKey extends Model
{
    use Log;
    public $table = 'settings_apikey';

    public $fillable = [
        'map_key', 'facebook_client_id', 'facebook_client_secret', 'google_client_id', 'google_client_secret','fcm_acc','fcm_key','fcm_token'
    ];

}
