<?php

namespace Jeidison\Filtrable\Operation;

use Illuminate\Database\Eloquent\Builder;

interface Operation
{
    public function addOperation(Builder $builder, array $inputs): Builder;
}
