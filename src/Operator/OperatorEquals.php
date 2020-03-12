<?php

namespace Jeidison\Filtrable\Operator;

use Illuminate\Database\Eloquent\Builder;

class OperatorEquals implements Operator
{

    public function add(Builder $builder, $operator, $column, $value): Builder
    {
        return $builder->where($column, '=', $value);
    }
}
