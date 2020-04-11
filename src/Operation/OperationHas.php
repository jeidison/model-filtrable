<?php

namespace Jeidison\Filtrable\Operation;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;

class OperationHas implements Operation
{

    public function addOperation(Builder $builder, array $inputs): Builder
    {
        $has = Arr::get($inputs, 'has');
        if (empty($has))
            return $builder;

        return $builder->has($has);
    }
}
