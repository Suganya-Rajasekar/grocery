<?php

namespace App\Repositories;

use App\Models\subscription_plans;
use App\Repositories\BaseRepository;

/**
 * Class subscription_plansRepository
 * @package App\Repositories
 * @version October 9, 2020, 7:06 am UTC
*/

class subscription_plansRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name',
        'price_month',
        'price_year',
        'subscriptions_count',
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
        return subscription_plans::class;
    }
}
