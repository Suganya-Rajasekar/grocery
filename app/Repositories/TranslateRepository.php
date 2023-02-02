<?php

namespace App\Repositories;

use App\Models\Translate;
use App\Repositories\BaseRepository;

/**
 * Class TranslateRepositry
 * @package App\Repositories
 * @version October 9, 2020, 7:06 am UTC
*/

class TranslateRepository extends BaseRepository
{

     protected $fieldSearchable = [
         'tbl','lng','field','field_fk','type','key','content','created_at','updated_at'
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
        return Translate::class;
    }

}
