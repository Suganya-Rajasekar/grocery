<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Testimonials;

class UpdateTestimonialsRequest extends FormRequest
{

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = Testimonials::$rules;
        if($this->_method == 'PATCH')
        {
            unset($rules['image']);
        }
        return $rules;
    }
}
