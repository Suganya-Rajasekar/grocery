<?php

namespace App\Repositories;

use App\Models\Addon;
use App\Repositories\BaseRepository;

/**
 * Class AddonRepository
 * @package App\Repositories
 * @version October 9, 2020, 7:06 am UTC
*/

class  AddonRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name',
        'image',
        'category',
        'price',
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
        return Addon::class;
    }

    public function image_path()
    {
        return Addon::IMAGE_PATH;
    }
}
