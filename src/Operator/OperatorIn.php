<?php

namespace Jeidison\Filtrable\Operator;

use Illuminate\Database\Eloquent\Builder;

class OperatorIn implements Operator
{

    public function add(Builder $builder, $operator, $column, $value): Builder
    {
        $values = array_map('trim', explode(',', $value));
        return $builder->whereIn($column, $values);
    }
}
