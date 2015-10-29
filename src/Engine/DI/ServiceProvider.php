<?php namespace Engine\DI;

use Phalcon\Di\InjectionAwareInterface;

interface ServiceProvider extends InjectionAwareInterface
{
    /**
     * On system booting
     *
     * @return mixed
     */
    public function boot();

    /**
     * On system ready to run
     *
     * @return mixed
     */
    public function ready();
}