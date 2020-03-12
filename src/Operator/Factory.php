<?php

namespace Jeidison\Filtrable\Operator;

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

    public static function getOperator($type = ''): Operator
    {
        $operator = self::getValue($type) ?? self::getValue('equals');

        return new $operator();
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
