<?php namespace Engine\Db;

use Engine\Db\Query\Contract as Query;
use Engine\Db\Model\Contract as Model;

interface Contract
{
    /**
     * Create new query
     *
     * @param Model $model
     * @return Query
     */
    public static function newQuery(Model $model = null);
}