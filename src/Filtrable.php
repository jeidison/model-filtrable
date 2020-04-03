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
        $self     = new self();
        $class    = new ReflectionClass(self::class);
        $property = $class->getProperty('fillable');
        $property->setAccessible(true);

        $propertPrimaryKey = $class->getProperty('primaryKey');
        $propertPrimaryKey->setAccessible(true);

        $fillable   = $property->getValue($self);
        $primaryKey = $propertPrimaryKey->getValue($self);

        array_push($fillable, $primaryKey);
        return $fillable;
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
