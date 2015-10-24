<?php namespace Engine\Db\Query;

use Engine\Db\Query\Builder\Contract as Builder;

class Factory implements Contract
{
    /**
     * @var Builder
     */
    protected $builder;

    public function getBuilder()
    {
        // TODO: Implement getBuilder() method.
        return $this->builder;
    }

    public function setBuilder(Builder $builder)
    {
        // TODO: Implement setBuilder() method.
        $this->builder = $builder;
    }
}