<?php

namespace App\Repositories;

use App\Models\Service;
use App\Repositories\BaseRepository;

/**
 * Class subscription_plansRepository
 * @package App\Repositories
 * @version October 9, 2020, 7:06 am UTC
*/

class ServiceRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name',
        'price',
        'description',
        'created_dt'
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
        return Service::class;
    }

}
