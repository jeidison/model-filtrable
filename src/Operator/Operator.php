<?php

namespace Jeidison\Filtrable\Operator;

use Illuminate\Database\Eloquent\Builder;

interface Operator
{
    public function add(Builder $builder, $operator, $column, $value);
}
