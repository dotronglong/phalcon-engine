<?php namespace Engine\Db\Query;

use Engine\Db\Query\Builder\Contract as Builder;

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
}