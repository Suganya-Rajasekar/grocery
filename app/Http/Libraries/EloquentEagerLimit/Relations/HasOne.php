<?php

namespace App\Http\Libraries\EloquentEagerLimit\Relations;

use Illuminate\Database\Eloquent\Relations\HasOne as Base;

class HasOne extends Base
{
    use HasLimit;
}
