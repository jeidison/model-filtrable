<?php

namespace Jeidison\Filtrable;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Jeidison\Filtrable\Operator\Factory;
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
    public static function columnsFiltrable()
    {
        $class    = new ReflectionClass(self::class);
        $property = $class->getProperty('fillable');
        $property->setAccessible(true);

        return $property->getValue(new self());
    }

    /**
     * @param Builder $query
     * @param array $inputs
     * @return Builder
     */
    public function scopeFilter(Builder $query, array $inputs = [])
    {
        $inputs = (!empty($inputs) ? $inputs : request()->all());
        $query  = $this->addFilter($query, $inputs);
        $query  = $this->addField($query, $inputs);
        $query  = $this->addWith($query, $inputs);
        $query  = $this->addFilterRelationship($query, $inputs);

        return $this->addOrderBy($query, $inputs);
    }

    //TODO: Melhorar, e deixar recursivo
    private function addFilterRelationship(Builder $builder, array $inputs)
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

    private function addFilter(Builder $builder, array $inputs): Builder
    {
        $builder = $this->addOperator($builder, $inputs);
        $filter  = Arr::only($inputs, $builder->getModel()::columnsFiltrable());

        return $builder->where($filter);
    }

    private function addOperator(Builder $builder, array $inputs): Builder
    {
        Arr::where($inputs, function ($value, $key) use ($builder) {
            if (!Str::contains($key, ':'))
                return;

            $keyExploded = explode(':', $key);
            $column      = Arr::first($keyExploded);
            $column      = Arr::only([$column => $value], $builder->getModel()::columnsFiltrable());
            if (empty($column))
                return;

            $operator  = Arr::get($keyExploded, 1);
            $operation = Factory::getOperator($operator);
            $operation->add($builder, $operator, key($column), $value);
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
        $with = Arr::get($inputs, 'with');
        $with = empty($with) ? [] : array_map('trim', explode(',', $with));

        return $builder->with($with);
    }

    private function addOrderBy(Builder $builder, array $inputs): Builder
    {
        $order = Arr::get($inputs, 'order');

        return $builder->orderBy($order ?? $this->getKeyName());
    }
}
