<?php

namespace App\Repositories;

use App\Models\SiteSetting;
use App\Repositories\BaseRepository;

/**
 * Class rolesRepository
 * @package App\Repositories
 * @version November 2, 2020, 12:53 pm UTC
*/

class SettingRepository extends BaseRepository
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
        return SiteSetting::class;
    }
}
