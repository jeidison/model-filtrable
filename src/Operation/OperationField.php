<?php

namespace Jeidison\Filtrable\Operation;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Jeidison\Filtrable\Operator\Factory;

class OperationField implements Operation
{

    public function addOperation(Builder $builder, array $inputs): Builder
    {
        Arr::where($inputs, function ($value, $key) use ($builder) {
            if (!Str::contains($key, ':'))
                return;

            $keyExploded = explode(':', $key);
            $column      = Arr::first($keyExploded);
            $column      = Arr::only([$column => $value], $builder->getModel()::columnsFiltrable());
            if (empty($column))
                return;

            $operator  = Arr::get($keyExploded, 1);
            $operation = Factory::getOperator($operator);
            $operation->add($builder, $operator, key($column), $value);
        });

        return $builder;
    }
}
