<?php namespace Engine\DI;

use Phalcon\Di\InjectionAwareInterface;

interface ServiceRegister extends InjectionAwareInterface
{
    /**
     * On system booting
     *
     * @return mixed
     */
    public function onBoot();

    /**
     * On system ready to run
     *
     * @return mixed
     */
    public function onReady();
}