<?php namespace Engine\Config;

use Engine\Exception\InvalidParameterException;

class Factory implements Contract
{
    /**
     * @var string
     */
    protected $delimiter = '.';

    /**
     * @var array
     */
    protected $data = [];

    public function has($name)
    {
        // TODO: Implement has() method.
        return isset($this->data[$name]);
    }

    public function get($name, $default = null)
    {
        // TODO: Implement get() method.
        $value = $this->data;
        $args  = explode($this->delimiter, $name);
        foreach ($args as $key) {
            if (isset($value[$key])) {
                $value = $value[$key];
            } else {
                $value = $default;
                break;
            }
        }

        return $value;
    }

    public function set($name, $value = null)
    {
        // TODO: Implement set() method.
        $this->data[$name] = $value;
    }

    public function sets(array $data, $merge = false)
    {
        // TODO: Implement sets() method.
        if ($merge) {
            $this->data = array_merge($this->data, $data);
        } else {
            $this->data = $data;
        }
    }

    public function gets()
    {
        // TODO: Implement gets() method.
        return $this->data;
    }

}