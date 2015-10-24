<?php namespace Engine\Db\Query;

use Engine\Db\Query\Builder\Contract as Builder;
use Phalcon\Mvc\Model\ResultsetInterface as ResultSet;

interface Contract
{
    /**
     * Get Builder
     *
     * @return Builder
     */
    public function getBuilder();

    /**
     * Set Builder
     *
     * @param Builder $builder
     * @return void
     */
    public function setBuilder(Builder $builder);

    /**
     * Fetch only one row
     *
     * @param array|null $columns
     * @return ResultSet
     */
    public function fetchRow($columns = null);

    /**
     * Fetch all rows
     *
     * @param array|null $columns
     * @return ResultSet
     */
    public function fetch($columns = null);
}