<?php

namespace Jeidison\Filtrable\Operation;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;

class OperationOrderBy implements Operation
{

    public function addOperation(Builder $builder, array $inputs): Builder
    {
        $order = Arr::get($inputs, 'order');

        return $builder->orderBy($order ?? $builder->getModel()->getKeyName());
    }
}
