<?php namespace Engine\DI;

interface ServiceProvider
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