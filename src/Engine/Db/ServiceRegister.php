<?php namespace Engine\Db;

use Engine\DI\Contract as DI;
use Engine\DI\ServiceRegister as ServiceRegisterContract;
use Phalcon\Db\Adapter\Pdo\Mysql;
use Phalcon\Db\Adapter\Pdo\Oracle;
use Phalcon\Db\Adapter\Pdo\Postgresql;
use Phalcon\Db\Adapter\Pdo\Sqlite;
use Phalcon\Mvc\Model\Manager as ModelsManager;
use Engine\Db\Query\Contract as QueryContract;
use Engine\Db\Query\Factory as Query;
use Engine\Db\Query\Builder\Contract as QueryBuilderContract;
use Engine\Db\Query\Builder\Factory as QueryBuilder;
use Engine\DI\HasInjection;

class ServiceRegister implements ServiceRegisterContract
{
    use HasInjection;

    public function onBoot()
    {
        // TODO: Implement onBoot() method.
        $this->registerDb();
        $this->registerModel();
        $this->registerQuery();
        $this->registerQueryBuilder();
    }

    public function onReady()
    {
        // TODO: Implement onReady() method.
    }

    protected function registerDb()
    {
        $this->getDI()->setShared('db', function() {
            $driver = env('DB_DRIVER', 'mysql');
            $config = [
                'host'       => env('DB_HOST'),
                'username'   => env('DB_USER'),
                'password'   => env('DB_PASS'),
                'dbname'     => env('DB_NAME'),
                'charset'    => env('DB_CHARSET', 'utf8')
            ];
            switch ($driver) {
                case 'mysql':
                default:
                    $config['persistent'] = env('DB_PERSISTENT', true);
                    return new Mysql($config);

                case 'oracle':
                    $config['charset'] = env('DB_CHARSET', 'AL32UTF8');
                    return new Oracle($config);

                case 'postgresql':
                    $config['schema'] = env('DB_SCHEMA', 'public');
                    return new Postgresql($config);

                case 'sqlite':
                    return new Sqlite([
                        'dbname' => env('DB_NAME')
                    ]);
            }
        });
    }

    protected function registerModel()
    {
        $this->getDI()->setShared('modelsManager', function() {
            return new ModelsManager();
        });
    }

    protected function registerQuery()
    {
        $this->getDI()->set(QueryContract::class, Query::class);
    }

    protected function registerQueryBuilder()
    {
        $this->getDI()->set(QueryBuilderContract::class, QueryBuilder::class);
    }
}