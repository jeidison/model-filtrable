<?php

namespace Jeidison\Filtrable\Operator;

use Exception;
use ReflectionClass;

class Factory
{
    const in         = OperatorIn::class;
    const notIn      = OperatorNotIn::class;
    const between    = OperatorBetween::class;
    const notBetween = OperatorNotBetween::class;
    const null       = OperatorIsNull::class;
    const notNull    = OperatorIsNotNull::class;
    const equals     = OperatorEquals::class;
    const whereDate  = OperatorWhereDate::class;

    public static function getOperator($type = ''): ?Operator
    {
        if (empty($type)) {
            $operator = self::getValue('equals');
            return new $operator();
        }

        $operator = self::getValue($type);
        if (empty($operator))
            return null;

        $instance = new $operator();
        throw_if(!is_subclass_of($instance, Operator::class), new Exception(get_class($instance) . "Class should implements interface ". Operator::class));

        return $instance;
    }

    private static function getConstants(): array
    {
        $reflect = new ReflectionClass(get_called_class());

        return $reflect->getConstants();
    }

    private static function getValue(string $key)
    {
        return static::getConstants()[$key] ?? null;
    }
}
