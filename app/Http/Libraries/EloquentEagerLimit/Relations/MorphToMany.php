<?php

namespace App\Http\Libraries\EloquentEagerLimit\Relations;

use Illuminate\Database\Eloquent\Relations\MorphToMany as Base;

class MorphToMany extends Base
{
    use BelongsOrMorphToMany;
}
