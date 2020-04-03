<?php

namespace Jeidison\Filtrable\Operator;

use Illuminate\Database\Eloquent\Builder;

class OperatorIsNotNull implements Operator
{

    public function add(Builder $builder, $operator, $column, $value): Builder
    {
        return $builder->whereNotNull($column);
    }
}
