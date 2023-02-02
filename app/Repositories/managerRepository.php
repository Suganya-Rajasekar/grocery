<?php

namespace App\Repositories;

use App\Models\manager;
use App\Repositories\BaseRepository;

/**
 * Class managerRepository
 * @package App\Repositories
 * @version October 27, 2020, 10:05 am UTC
*/

class managerRepository extends BaseRepository
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
        return manager::class;
    }
}
