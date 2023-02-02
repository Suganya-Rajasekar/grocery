<?php

namespace App\Repositories;

use App\Models\samples;
use App\Repositories\BaseRepository;

/**
 * Class samplesRepository
 * @package App\Repositories
 * @version October 1, 2020, 7:26 am UTC
*/

class samplesRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name'
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
        return samples::class;
    }
}
