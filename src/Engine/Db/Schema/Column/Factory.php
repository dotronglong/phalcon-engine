<?php namespace Engine\Db\Schema\Column;

abstract class Factory implements Contract
{
    /**
     * @var mixed
     */
    protected $default;

    /**
     * @var bool
     */
    protected $nullable = false;

    /**
     * @var bool
     */
    protected $unsigned = false;

    public function setDefault($value = null)
    {
        // TODO: Implement setDefault() method.
        $this->default = $value;
        return $this;
    }

    public function nullable()
    {
        // TODO: Implement nullable() method.
        $this->nullable = true;
        return $this;
    }

    public function unsigned()
    {
        // TODO: Implement unsigned() method.
        $this->unsigned = true;
        return $this;
    }
}