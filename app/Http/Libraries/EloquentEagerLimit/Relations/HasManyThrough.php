<?php

namespace App\Http\Libraries\EloquentEagerLimit\Relations;

use Illuminate\Database\Eloquent\Relations\HasManyThrough as Base;

class HasManyThrough extends Base
{
    use HasOneOrManyThrough;
}
