<?php namespace Engine\Db;

use Engine\DI\Contract as DI;
use Engine\DI\ServiceProvider as ServiceProviderContract;
use Phalcon\Db\Adapter\Pdo\Mysql;
use Phalcon\Db\Adapter\Pdo\Oracle;
use Phalcon\Db\Adapter\Pdo\Postgresql;
use Phalcon\Db\Adapter\Pdo\Sqlite;

class ServiceProvider implements ServiceProviderContract
{

    public function boot(DI $di)
    {
        // TODO: Implement boot() method.
        $di->setShared('db', function() {
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

    public function ready()
    {
        // TODO: Implement ready() method.
    }
}