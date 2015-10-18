<?php namespace Engine\DI;

use Phalcon\DiInterface;

interface Contract extends DiInterface
{
    /**
     * Use to process service providers
     *
     * @return mixed
     */
    public function registerServiceProviders();

    /**
     * Use to run service providers on system ready
     *
     * @return mixed
     */
    public function runServiceProviders();
}