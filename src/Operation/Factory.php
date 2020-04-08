<?php

namespace Jeidison\Filtrable\Operation;

use Exception;
use ReflectionClass;

class Factory
{
    const with         = OperationWith::class;
    const field        = OperationField::class;
    const filter       = OperationFilter::class;
    const orderBy      = OperationOrderBy::class;
    const relationship = OperationRelationship::class;
    const paginate     = OperationPaginate::class;
    const has          = OperationHas::class;

    public static function createOperations(): array
    {
        return array_values(array_map(function ($operation) {
            $instance = new $operation;
            throw_if(!is_subclass_of($instance, Operation::class), new Exception(get_class($instance) . "Class should implements interface " . Operation::class));
            return new $instance;
        }, self::getConstants()));
    }

    public static function getOperation($type): Operation
    {
        $operation = self::getValue($type);
        throw_if(empty($operation), new Exception("Operation not found"));
        return new $operation();
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
