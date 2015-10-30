<?php namespace Engine\DI\Service;

use Engine\Exception\MethodNotFoundException;
use Phalcon\Di\ServiceInterface;

interface Contract extends ServiceInterface
{
    /**
     * @param object  $object
     * @param string  $method
     * @param null    $parameters
     * @return mixed
     * @throws MethodNotFoundException
     */
    public static function resolveMethod($object, $method, $parameters = null);
}