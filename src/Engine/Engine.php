<?php namespace Engine;

use Engine\Exception\ClassNotFoundException;
use Engine\Exception\NullPointerException;
use ReflectionClass;
use ReflectionException;

final class Engine
{
    /**
     * Create new instance
     *
     * @return mixed
     */
    public static function newInstance()
    {
        $args = func_get_args();
        if (count($args) === 0) {
            throw new NullPointerException('Class name must be defined!');
        }

        $className = $args[0];
        unset($args[0]);

        try {
            $rc = new ReflectionClass($className);
            if (count($args)) {
                return $rc->newInstanceArgs($args);
            } else {
                return $rc->newInstance();
            }
        } catch (ReflectionException $e) {
            throw new ClassNotFoundException("Class $className could not be found.");
        }
    }
}