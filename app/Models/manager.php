<?php

namespace App\Models;

use Eloquent as Model;
use App\Models\UserModule;
use App\Models\roles;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class manager
 * @package App\Models
 * @version October 27, 2020, 10:05 am UTC
 *
 * @property integer $role
 * @property string $name
 * @property string $email
 * @property string $phone_number
 * @property string $business_name
 * @property string $address
 * @property string $website
 * @property string|\Carbon\Carbon $email_verified_at
 * @property integer $status
 * @property string $password
 * @property string $remember_token
 * @property string $google_id
 * @property string $fb_id
 * @property string $pass_gen
 */
class manager extends Model
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
        'website',
        'email_verified_at',
        'status',
        'password',
        'remember_token',
        'google_id',
        'fb_id',
        'first_login',
        'pass_gen',
        'sub_role'
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
        'status' => 'integer',
        'password' => 'string',
        'remember_token' => 'string',
        'google_id' => 'string',
        'fb_id' => 'string',
        'pass_gen' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'sub_role' => 'integer',
        'role' => 'integer',
        'name' => 'required|string|max:255',
        'email' => 'required|email|max:255|min:5|unique:users',
        'phone_number' => 'nullable|string|max:20',
        'business_name' => 'nullable|string|max:100',
        'address' => 'nullable|string',
        'website' => 'nullable|string',
        'email_verified_at' => 'nullable',
        'status' => 'integer',
        // 'password' => 'required|string|max:255',
        'remember_token' => 'nullable|string|max:100',
        'google_id' => 'nullable|string',
        'fb_id' => 'nullable|string',
        'pass_gen' => 'nullable|string',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'deleted_at' => 'nullable'
    ];

    public function newQuery($excludeDeleted = true) {
        return parent::newQuery($excludeDeleted)
            ->where('role', 3);
    }

    public function getAccessAttribute()
    {
        $res = UserModule::select('access')->where('user_id',$this->id)->first();
        return !empty($res) ? json_decode($res->access): '';
    }

    public function getSubRoleNameAttribute()
    {
        $name = roles::select('name')->where('id',$this->sub_role)->pluck('name')->first();
        return !empty($name) ? $name: '';
    }
    
}
