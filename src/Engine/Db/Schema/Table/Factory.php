<?php namespace Engine\Db\Schema\Table;

use Engine\Db\Schema\Column\Contract as Column;

abstract class Factory implements Contract
{
    /**
     * @var Column[]
     */
    protected $columns = [];

    /**
     * @var string
     */
    protected $name;

    /**
     * @var array
     */
    protected $options = [];

    public function removeColumn($name)
    {
        // TODO: Implement removeColumn() method.
        if (isset($this->columns[$name])) {
            unset($this->columns[$name]);
        }
    }

    public function setName($name)
    {
        // TODO: Implement setName() method.
        $this->name = $name;
        return $this;
    }

    public function getName()
    {
        // TODO: Implement getName() method.
        return $this->name;
    }

    public function setOption($key, $value)
    {
        // TODO: Implement setOption() method.
        $this->options[$key] = $value;
        return $this;
    }

    public function getOption($key, $default = null)
    {
        // TODO: Implement getOption() method.
        return isset($this->options[$key]) ? $this->options[$key] : $default;
    }

    public function removeOption($key)
    {
        // TODO: Implement removeOption() method.
        if (isset($this->options[$key])) {
            unset($this->options[$key]);
        }
        return $this;
    }
}