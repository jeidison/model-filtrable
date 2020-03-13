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
            if (Str::contains($key, '->')) {
                $keyExploded = explode('->', $key);
                $relation = Arr::first($keyExploded);
                $builder->whereHas($relation, function (Builder $query) use ($key, $value, $keyExploded) {
                    if (count($keyExploded) > 2) {

                        $relation = explode('->', Arr::get(explode('->', $key, 2), 1));
                        $query->whereHas(Arr::first($relation), function (Builder $query2) use ($value, $key) {
                            $column = Arr::get(explode('->', $key, 2), 1);

                            $query2->where(Str::replaceFirst('->', '.', $column), $value);
                        });

                    } else {
                        $query->where(Str::replaceFirst('->', '.', $key), $value);
                    }
                });
            }
        });

        return $builder;
    }
}
