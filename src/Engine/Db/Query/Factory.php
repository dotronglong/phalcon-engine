<?php namespace Engine\Db\Query;

use Engine\Db\Query\Builder\Contract as Builder;
use Phalcon\Mvc\Model\ResultsetInterface as ResultSet;

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

    public function fetchRow($columns = null)
    {
        // TODO: Implement fetchRow() method.
        if (!is_null($columns) && is_array($columns)) {
            $this->builder->columns($columns);
        }

        return $this->builder->getQuery()->getSingleResult();
    }

    public function fetch($columns = null)
    {
        // TODO: Implement fetch() method.
        if (!is_null($columns) && is_array($columns)) {
            $this->builder->columns($columns);
        }

        return $this->builder->getQuery()->execute();
    }
}