<?php namespace Engine;

use Phalcon\DiInterface as DI;
use Engine\Exception\ClassNotFoundException;
use Engine\Shared\HasSingleton;
use ReflectionClass;
use ReflectionException;

final class Engine
{
    use HasSingleton;

    /**
     * @var DI
     */
    public $di;

    /**
     * Create new instance
     *
     * @param       $name
     * @param array $args
     * @return object
     * @throws ClassNotFoundException
     */
    public static function newInstance($name, $args = [])
    {
        try {
            $rc = new ReflectionClass($name);
            if (is_array($args) && count($args)) {
                return $rc->newInstanceArgs($args);
            } else {
                return $rc->newInstance();
            }
        } catch (ReflectionException $e) {
            throw new ClassNotFoundException("Class $name could not be found.");
        }
    }
}