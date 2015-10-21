<?php namespace Engine\DI;

use Contract as DI;

interface ServiceProvider
{
    /**
     * On system booting
     *
     * @param DI $di
     * @return mixed
     */
    public function boot(DI $di);

    /**
     * On system ready to run
     *
     * @return mixed
     */
    public function ready();
}