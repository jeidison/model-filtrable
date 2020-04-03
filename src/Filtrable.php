<?php

namespace Jeidison\Filtrable;

use Illuminate\Database\Eloquent\Builder;
use Jeidison\Filtrable\Operation\Factory;
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
     * @param Builder $builder
     * @param array $inputs
     * @return Builder
     */
    public function scopeFilter(Builder $builder, array $inputs = [])
    {
        $operations = Factory::createOperations();
        foreach ($operations as $operation) {
            $builder = $operation->addOperation($builder, $inputs);
        }

        return $builder;
    }
}
