<?php namespace Engine\Config;

interface Contract
{
    /**
     * Has config with name
     * @param string $name
     * @return boolean
     */
    public function has($name);

    /**
     * Get configuration
     *
     * @param string $name
     * @param null   $default
     * @return mixed
     */
    public function get($name, $default = null);

    /**
     * Set configuration
     *
     * @param string $name
     * @param mixed  $value
     * @return void
     */
    public function set($name, $value = null);

    /**
     * Set array config
     *
     * @param array      $data
     * @param bool|false $merge
     * @return mixed
     */
    public function sets(array $data, $merge = false);

    /**
     * @return array
     */
    public function gets();
}