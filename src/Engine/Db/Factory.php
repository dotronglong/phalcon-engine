<?php namespace Engine\Db;

use Engine\Db\Model\Contract as Model;
use Engine\Db\Query\Contract as Query;
use Engine\Db\Query\Builder\Contract as QueryBuilder;

class Factory implements Contract
{
    public static function newQuery(Model $model = null)
    {
        // TODO: Implement newQuery() method.
        $query   = di()->get(Query::class);
        $builder = di()->get(QueryBuilder::class);
        $query->setBuilder($builder);

        return $query;
    }

}