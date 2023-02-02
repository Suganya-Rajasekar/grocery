<?php

namespace App\Traits;

trait LikeScopeTrait {

    public function scopeWhereLike($query, $column, $value)
	{
	    return $query->where($column, 'like', '%'.$value.'%');
	}

	public function scopeOrWhereLike($query, $column, $value)
	{
	    return $query->orWhere($column, 'like', '%'.$value.'%');
	}


}