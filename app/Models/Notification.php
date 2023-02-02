<?php 
namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use App\Models\Translate;
use App\Models\Addon;

/**
 * Class category
 * @package App\Models
 * @version Apr 13, 2021
 * @author Dharma
 * @property string|\Carbon\Carbon $created_dt
 */
class Notification extends Model  {
	protected $table		= 'tb_notification';
	protected $primaryKey	= 'id';

	/**
    * Get the user.
    */
	public  function from_user_info()
	{
		return $this->belongsTo(User::class,'from', 'id');
	}

	/**
    * Get the user.
    */
	public  function to_user_info()
	{
		return $this->belongsTo(User::class, 'to', 'id');
	}
}