<?php

namespace Jeidison\Filtrable\Operation;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;

class OperationField implements Operation
{

    public function addOperation(Builder $builder, array $inputs): Builder
    {
        $fields = Arr::get($inputs, 'fields');
        $fields = empty($fields) ? ['*'] : array_map('trim', explode(',', $fields));

        return $builder->select($fields);
    }
}
