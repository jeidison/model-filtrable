<?php

namespace Jeidison\Filtrable\Operation;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;

class OperationWith implements Operation
{

    public function addOperation(Builder $builder, array $inputs): Builder
    {
        $with = Arr::get($inputs, 'with');
        $with = empty($with) ? [] : array_map('trim', explode(',', $with));

        return $builder->with($with);
    }
}
