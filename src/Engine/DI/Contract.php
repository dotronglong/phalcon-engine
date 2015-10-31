<?php namespace Engine\DI;

use Phalcon\DiInterface;
use Phalcon\Events\EventsAwareInterface;

interface Contract extends DiInterface, EventsAwareInterface
{
    /**
     * Apply InjectAware and EventsAware to instance
     *
     * @param $instance
     * @return mixed
     */
    public static function applyAwareInterface($instance);
}