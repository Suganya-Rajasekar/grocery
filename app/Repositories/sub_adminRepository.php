<?php

namespace App\Repositories;

use App\Models\sub_admin;
use App\Repositories\BaseRepository;

/**
 * Class sub_adminRepository
 * @package App\Repositories
 * @version October 27, 2020, 10:27 am UTC
*/

class sub_adminRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
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
        'pass_gen'
    ];

    /**
     * Return searchable fields
     *
     * @return array
     */
    public function getFieldsSearchable()
    {
        return $this->fieldSearchable;
    }

    /**
     * Configure the Model
     **/
    public function model()
    {
        return sub_admin::class;
    }
}
