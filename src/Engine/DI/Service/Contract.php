<?php namespace Engine\DI\Service;

use Engine\Exception\BindingResolutionException;
use Engine\Exception\MethodNotFoundException;
use Phalcon\Di\ServiceInterface;
use Engine\Exception\ClassNotFoundException;

interface Contract extends ServiceInterface
{
    /**
     * Resolve object's method
     *
     * @param object  $object
     * @param string  $method
     * @param null    $parameters
     * @return mixed
     * @throws MethodNotFoundException
     */
    public static function resolveMethod($object, $method, $parameters = null);

    /**
     * Resolve instance by name
     *
     * @param string $name
     * @param null   $parameters
     * @return mixed
     * @throws BindingResolutionException
     * @throws ClassNotFoundException
     */
    public static function resolveInstance($name, $parameters = null);
}