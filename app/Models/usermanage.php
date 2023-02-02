<?php

namespace App\Models;

use Eloquent as Model;
use App\Models\UserSubscription;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class usermanage
 * @package App\Models
 * @version October 6, 2020, 12:58 pm UTC
 *
 * @property integer $role
 * @property string $name
 * @property string $email
 * @property string $phone_number
 * @property string $business_name
 * @property string $address
 * @property string $website
 * @property string|\Carbon\Carbon $email_verified_at
 * @property string $password
 * @property string $remember_token
 */
class usermanage extends Model
{
    use SoftDeletes;

    public $table = 'users';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];



    public $fillable = [
        'role',
        'name',
        'email',
        'phone_number',
        'business_name',
        'address',
        'status',
        'website',
        'email_verified_at',
        'password',
        'remember_token'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'role' => 'integer',
        'name' => 'string',
        'email' => 'string',
        'phone_number' => 'string',
        'business_name' => 'string',
        'address' => 'string',
        'website' => 'string',
        'email_verified_at' => 'datetime',
        'password' => 'string',
        'remember_token' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'role' => 'required|integer',
        'name' => 'required|string|max:255',
        'email' => 'required|string|max:255',
        'phone_number' => 'nullable|string|max:20',
        'business_name' => 'nullable|string|max:100',
        'address' => 'nullable|string',
        'website' => 'nullable|string|max:100',
        'email_verified_at' => 'nullable',
        'password' => 'required|string|max:255',
        'remember_token' => 'nullable|string|max:100',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'deleted_at' => 'nullable'
    ];
    public function newQuery($excludeDeleted = true) {
        return parent::newQuery($excludeDeleted)
            ->where('role', 5);
    }

    function getSubsCountAttribute()
    {
        return UserSubscription::where('user_id',$this->id)->count();
    }
}
