<?php

namespace App\Repositories;

use App\Models\vendor;
use App\Repositories\BaseRepository;

/**
 * Class vendorRepository
 * @package App\Repositories
 * @version October 6, 2020, 10:48 am UTC
*/

class vendorRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'business_name',
        'address',
        'phone_number',
        'email',
        'status',
        'name',
        'password',
        'website'
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
        return vendor::class;
    }

    
}
