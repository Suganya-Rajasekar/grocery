<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Homeeventsection extends Model
{
    use HasFactory;

    protected $table = 'home_event_section';

    protected $fillable = ['meal_section_name','theme_section_name','preference_section_name','addon_section_name'];

}
