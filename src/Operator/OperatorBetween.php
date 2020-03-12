<?php

namespace Jeidison\Filtrable\Operator;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;

class OperatorBetween implements Operator
{

    public function add(Builder $builder, $operator, $column, $value): Builder
    {
        $values = array_map(function ($value) {
            if (strtotime($value))
                return Carbon::createFromTimestamp(strtotime($value));
            return $value;
        }, explode(',', $value));

        return $builder->whereBetween($column, $values);
    }
}
