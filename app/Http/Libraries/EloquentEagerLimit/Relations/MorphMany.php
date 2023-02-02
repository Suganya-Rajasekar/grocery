<?php

namespace App\Http\Libraries\EloquentEagerLimit\Relations;

use Illuminate\Database\Eloquent\Relations\MorphMany as Base;

class MorphMany extends Base
{
    use HasLimit;
}
