<?php

namespace App\Repositories;

use App\Models\usermanage;
use App\Repositories\BaseRepository;

/**
 * Class usermanageRepository
 * @package App\Repositories
 * @version October 6, 2020, 12:58 pm UTC
*/

class usermanageRepository extends BaseRepository
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
        'password',
        'remember_token'
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
        return usermanage::class;
    }

    public function withTrashed()
    {
        return usermanage::withTrashed();
    }
}
