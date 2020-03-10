<?php

namespace Jeidison\Filtrable;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use ReflectionClass;
use ReflectionException;

/**
 * @author Jeidison Farias <jeidison.farias@gmail.com>
 * @method static filter($inputs = []);
 */
trait Filtrable
{
    /**
     * @return array
     * @throws ReflectionException
     */
    public static function columns()
    {
        $class = new ReflectionClass(self::class);
        $property = $class->getProperty('fillable');
        $property->setAccessible(true);

        return $property->getValue(new self());
    }

    /**
     * @param Builder $query
     * @param $inputs
     * @return Builder
     */
    public function scopeFilter($query, $inputs = [])
    {
        $inputs = (!empty($inputs) ? $inputs : request()->all());
        $query  = $this->addFilter($query, $inputs);
        $query  = $this->addField($query, $inputs);
        $query  = $this->addWith($query, $inputs);
        $query  = $this->addFilterRelationship($query, $inputs);

        return $this->addOrderBy($query, $inputs);
    }

    private function addFilterRelationship(Builder $builder, array $inputs)
    {
        Arr::where($inputs, function ($value, $key) use ($builder) {
            if (Str::contains($key, '->')) {
                $relation = Arr::first(explode('->', $key));
                $builder->whereHas($relation, function (Builder $query) use ($key, $value) {
                    $query->where(Str::replaceFirst('->', '.', $key), $value);
                });
            }
        });

        return $builder;
    }

    private function addFilter(Builder $builder, array $inputs): Builder
    {
        $builder = $this->addLike($builder, $inputs);
        $filter  = Arr::only($inputs, self::columns());

        return $builder->where($filter);
    }

    private function addLike(Builder $builder, array $inputs): Builder
    {
        Arr::where($inputs, function ($value, $key) use ($builder) {
            if (Str::contains($key, ':like')) {
                $column = Arr::first(explode(':like', $key));
                $column = Arr::only([$column => $value], self::columns());
                if (!empty($column)) {
                    $builder->where(key($column), 'like', $value);
                }
            }
        });

        return $builder;
    }

    private function addField(Builder $builder, array $inputs): Builder
    {
        $fields = Arr::get($inputs, 'fields');
        $fields = empty($fields) ? ['*'] : array_map('trim', explode(',', $fields));

        return $builder->select($fields);
    }

    private function addWith(Builder $builder, array $inputs): Builder
    {
        $with = Arr::get($inputs, 'with', []);
        $with = empty($with) ? [] : array_map('trim', explode(',', $with));

        return $builder->with($with);
    }

    private function addOrderBy(Builder $builder, array $inputs): Builder
    {
        $order = Arr::get($inputs, 'order');

        return $builder->orderBy($order ?? $this->getKeyName());
    }
}
