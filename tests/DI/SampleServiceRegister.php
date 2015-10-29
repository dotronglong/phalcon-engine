<?php namespace Engine\Tests\DI;

use Engine\DI\ServiceRegister;
use Engine\DI\HasInjection;

class SampleServiceRegister implements ServiceRegister
{
    use HasInjection;

    public function onBoot()
    {
        // TODO: Implement onBoot() method.
    }

    public function onReady()
    {
        // TODO: Implement onReady() method.
    }

}