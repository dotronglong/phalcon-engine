<?php namespace Engine\Config;

use Engine\Exception\InvalidParameterException;

interface Contract
{
    /**
     * Get configuration
     *
     * @param string $name
     * @param null   $default
     * @return mixed
     *
     * @throws InvalidParameterException
     */
    public function get($name, $default = null);

    /**
     * Set configuration
     *
     * @param string $name
     * @param mixed  $value
     * @return static
     */
    public function set($name, $value = null);

    /**
     * Set array config
     *
     * @param array      $config
     * @param bool|false $merge
     * @return mixed
     */
    public function sets(array $config, $merge = false);
}