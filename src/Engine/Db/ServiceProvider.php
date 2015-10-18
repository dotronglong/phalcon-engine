<?php namespace Engine\Db;

use Engine\DI\ServiceProvider as ServiceProviderContract;

class ServiceProvider implements ServiceProviderContract
{

    public function boot()
    {
        // TODO: Implement boot() method.
        di()->setShared('db', function() {
            $config = [
                'driver'   => env('DB_DRIVER'),
                'host'     => env('DB_HOST'),
                'username' => env('DB_USER'),
                'password' => env('DB_PASS'),
                'name'     => env('DB_NAME')
            ];

            switch ($config['driver']) {
                case 'mysql':
                default:
                    return new \Phalcon\Db\Adapter\Pdo\Mysql($config);

                case 'oracle':
                    return new \Phalcon\Db\Adapter\Pdo\Oracle($config);

                case 'postgresql':
                    return new \Phalcon\Db\Adapter\Pdo\Postgresql($config);

                case 'sqlite':
                    return new \Phalcon\Db\Adapter\Pdo\Sqlite($config);
            }
        });
    }

    public function ready()
    {
        // TODO: Implement ready() method.
    }
}