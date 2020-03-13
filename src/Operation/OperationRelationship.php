<?php

namespace Jeidison\Filtrable\Operation;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class OperationRelationship implements Operation
{

    public function addOperation(Builder $builder, array $inputs): Builder
    {
        Arr::where($inputs, function ($value, $key) use ($builder) {
            if (!Str::contains($key, '->')) {
                return;
            }

            $keys   = explode('->', $key);
            $column = Arr::last($keys);
            Arr::forget($keys, count($keys) - 1);
            $relation = implode('.', $keys);
            $builder->whereHas($relation, function ($q) use ($column, $value) {
                $operation = Factory::getOperation('filter');
                return $operation->addOperation($q, [$column => $value]);
            });
        });

        return $builder;
    }
}
