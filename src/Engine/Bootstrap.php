<?php namespace Engine;

use Phalcon\Exception;

class Bootstrap
{

    /**
     * Global Bootstrap Instance
     *
     * @var \Engine\Bootstrap
     */
    protected static $instance;

    /**
     * Dependency Injection
     *
     * @var \Phalcon\DI\FactoryDefault
     */
    private $di;

    /**
     * Application
     *
     * @var \Phalcon\Mvc\Application
     */
    private $app;

    /**
     * Get Boostrap instance
     *
     * @return \Engine\Bootstrap
     */
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public static function app()
    {
        if (self::getInstance()->app === null) {
            self::getInstance()->app = new \Phalcon\Mvc\Application(self::getInstance()->di);
        }

        return self::getInstance()->app;
    }

    public static function run()
    {
        $boostrap     = self::getInstance();
        $boostrap->di = new \Phalcon\DI\FactoryDefault();

        new \Whoops\Provider\Phalcon\WhoopsServiceProvider($boostrap->di);

        $boostrap->setupSession()
            ->setupUrl()
            ->setupDatabase()
            ->setupRequest()
            ->setupRouter()
            ->setupModules();

        echo $boostrap->app()->handle()->getContent();
    }

    /**
     * Setup a base URI so that all generated URIs
     *
     * @return \Engine\Bootstrap
     */
    private function setupUrl()
    {
        $this->di->set('url', function () {
            $url = new \Phalcon\Mvc\Url();
            $url->setBaseUri(Engine::config()->app->base_url);

            return $url;
        });

        return $this;
    }

    /**
     * Start the session the first time a component requests the session service
     *
     * @return \Engine\Bootstrap
     */
    private function setupSession()
    {
        $this->di->set('session', function () {
            $session = new \Phalcon\Session\Adapter\Files();
            $session->start();

            return $session;
        });

        return $this;
    }

    /**
     * Database connection is created based on parameters defined in the configuration file
     *
     * @return \Engine\Bootstrap
     */
    private function setupDatabase()
    {
        $this->di->set('db', function () {
            $config = [
                'host'     => Engine::config()->database->host,
                'username' => Engine::config()->database->username,
                'password' => Engine::config()->database->password,
                'name'     => Engine::config()->database->name,
            ];

            switch (Engine::config()->database->driver) {
                case 'mysql':
                default:
                    return new \Phalcon\Db\Adapter\Pdo\Mysql($config);

                case 'oracle':
                    return new \Phalcon\Db\Adapter\Pdo\Mysql($config);

                case 'postgresql':
                    return new \Phalcon\Db\Adapter\Pdo\Postgresql($config);

                case 'sqlite':
                    return new \Phalcon\Db\Adapter\Pdo\Sqlite($config);
            }
        });

        return $this;
    }

    /**
     * Setup Application's request
     *
     * @return \Engine\Bootstrap
     */
    private function setupRequest()
    {
        $this->di->set('request', function () {
            return new \Phalcon\Http\Request();
        }, true);

        return $this;
    }

    /**
     * Setup Application's router
     *
     * @return \Engine\Bootstrap
     */
    private function setupRouter()
    {
        $this->di->set('router', function () {
            $fileRoutes = PATH_APP_CONFIG . DS . 'routes.php';
            if (file_exists($fileRoutes)) {
                require_once $fileRoutes;
            } else {
                // Create the router without default routes
                $router = new \Phalcon\Mvc\Router(false);

                //Setting a specific default
                $router->setDefaults([
                    'module'     => Engine::config()->app->default->module,
                    'controller' => Engine::config()->app->default->controller,
                    'action'     => Engine::config()->app->default->action
                ]);

                //Set 404 paths
                $router->notFound([
                    'module'     => 'core',
                    'controller' => 'error',
                    'action'     => 'route404'
                ]);

                // Remove trailing slashes automatically
                $router->removeExtraSlashes(true);

                // Add default routes
                $router->add('/', array(
                    'module' => 'core'
                ));
                $router->add('/:module', array(
                    'module' => 1
                ));
                $router->add('/:module/:controller', array(
                    'module'     => 1,
                    'controller' => 2
                ));
                $router->add('/:module/:controller/:action', array(
                    'module'     => 1,
                    'controller' => 2,
                    'action'     => 3
                ));
                $router->add('/:module/:controller/:action/:params', array(
                    'module'     => 1,
                    'controller' => 2,
                    'action'     => 3,
                    'params'     => 4
                ));
            }

            return $router;
        }, true);

        return $this;
    }

    /**
     * Setup Application's modules
     *
     * @return \Engine\Bootstrap
     */
    private function setupModules()
    {
        $this->app()->registerModules([
            'core'  => [
                'className' => 'App\Modules\Core\Module',
                'path'      => '../app/modules/core/Module.php'
            ],
            'basic' => [
                'className' => 'App\Modules\Basic\Module',
                'path'      => '../app/modules/basic/Module.php'
            ]
        ]);

        return $this;
    }
}
