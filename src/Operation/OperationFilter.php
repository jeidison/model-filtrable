<?php

namespace Jeidison\Filtrable\Operation;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Jeidison\Filtrable\Operator\Factory;

class OperationFilter implements Operation
{

    public function addOperation(Builder $builder, array $inputs): Builder
    {
        Arr::where($inputs, function ($value, $key) use ($builder, $inputs) {
            if (!Str::contains($key, ':')) {
                if (Str::contains($key, '.')) {
                    $filter = [$key => $value];
                } else {
                    $filter  = Arr::only($inputs, $builder->getModel()::columnsFiltrable());
                }
                $builder->where($filter);
                return;
            }

            $keyExploded = explode(':', $key);
            $column      = Arr::first($keyExploded);
            $column      = Arr::only([$column => $value], $builder->getModel()::columnsFiltrable());
            if (empty($column))
                return;

            $operator  = Arr::get($keyExploded, 1);
            $operation = Factory::getOperator($operator);
            if (!empty($operation)) {
                $operation->add($builder, $operator, key($column), $value);
            } else {
                $builder->where(key($column), $operator, $value);
            }
        });

        return $builder;
    }
}
