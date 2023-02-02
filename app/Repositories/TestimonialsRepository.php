<?php

namespace App\Repositories;

use App\Models\Testimonials;
use App\Repositories\BaseRepository;

/**
 * Class TestimonialsRepository
 * @package App\Repositories
 * @version October 9, 2020, 7:06 am UTC
*/

class TestimonialsRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name',
        'image',
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
        return Testimonials::class;
    }

    public function image_path()
    {
        return Testimonials::IMAGE_PATH;
    }
}
