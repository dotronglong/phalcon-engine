<?php namespace Engine\Db\Model\Presenter;

use Engine\Db\Model\Contract as Resource;

class Factory implements Contract
{
    /**
     * @var Resource
     */
    protected $resource;

    public function getResource()
    {
        // TODO: Implement getResource() method.
        return $this->resource;
    }

    public function setResource(Resource $resource)
    {
        // TODO: Implement setResource() method.
        $this->resource = $resource;
    }

    public function __get($name)
    {
        // TODO: Implement __get() method.
        return $this->resource->$name;
    }

    public function __set($name, $value)
    {
        // TODO: Implement __set() method.
        $this->resource->$name = $value;
    }

    public function __isset($name)
    {
        // TODO: Implement __isset() method.
        return isset($this->resource->$name);
    }


}