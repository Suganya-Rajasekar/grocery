<?php

namespace App\Http\Libraries\EloquentEagerLimit\Relations;

use Illuminate\Database\Eloquent\Relations\HasMany as Base;

class HasMany extends Base
{
    use HasLimit;
}
