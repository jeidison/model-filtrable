<?php

namespace Jeidison\Filtrable\Operation;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;

class OperationOrderBy implements Operation
{

    public function addOperation(Builder $builder, array $inputs): Builder
    {
        $orderStr = Arr::get($inputs, 'order');
        if (!$orderStr)
            return $builder->orderBy($builder->getModel()->getKeyName());

        $orderList = explode(',', $orderStr);
        foreach ($orderList as $orderField) {
            $order     = explode(':', $orderField);
            $field     = Arr::first($order);
            $direction = (count($order) > 1) ? Arr::last($order) : 'asc';
            $builder->orderBy($field, $direction);
        }

        return $builder;
    }
}
