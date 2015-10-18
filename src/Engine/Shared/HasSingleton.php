<?php namespace Engine\Shared;

trait HasSingleton
{
    /**
     * Global Engine Instance
     *
     * @var static
     */
    protected static $instance;

    /**
     * Get Engine instance
     *
     * @return static
     */
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }
}