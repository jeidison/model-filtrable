<?php

namespace Jeidison\Filtrable\Operation;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;

class OperationPaginate implements Operation
{

    public function addOperation(Builder $builder, array $inputs): Builder
    {
        $hasPaginate = Arr::exists($inputs, 'paginate') || Arr::exists($inputs, 'paginate:perPage') || Arr::exists($inputs, 'paginate:page');
        if ($hasPaginate) {
            $perPage = Arr::get($inputs, 'paginate:perPage');
            $builder->paginate( $perPage ?? '15');
            if (Arr::exists($inputs, 'paginate:page')) {
                $builder->offset(($perPage ?? '15') * Arr::get($inputs, 'paginate:page'));
            }
        }
        return $builder;
    }
}
