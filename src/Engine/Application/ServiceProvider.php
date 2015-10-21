<?php namespace Engine\Application;

use Engine\DI\Contract as DI;
use Phalcon\Mvc\Url;
use Phalcon\Session\Adapter\Files as Session;
use Engine\DI\ServiceProvider as ServiceProviderContract;
use Engine\Dispatcher\Factory as Dispatcher;
use Engine\Http\Request\Factory as Request;
use Engine\Http\Response\Factory as Response;
use Engine\Routing\Router;

class ServiceProvider implements ServiceProviderContract
{
    /**
     * @var DI 
     */
    protected $di;
    
    public function boot(DI $di)
    {
        // TODO: Implement boot() method.
        $this->di = $di;
        
        $this->registerDebugger()
             ->registerUrl()
             ->registerSession()
             ->registerDispatcher()
             ->registerRequest()
             ->registerResponse()
             ->registerRouter()
             ->registerView();
    }

    public function ready()
    {
        // TODO: Implement ready() method.
        
        // Load routes into router
        require PATH_FILE_ROUTES;
    }

    /**
     * Setup a base URI so that all generated URIs
     *
     * @return static
     */
    protected function registerUrl()
    {
        $this->di->setShared('url', function () {
            $protocol = stripos(server('SERVER_PROTOCOL'), 'https') === true ? 'https://' : 'http://';
            $hostname = server('HTTP_HOST');

            $url = new Url();
            $url->setStaticBaseUri(env('static_url', "$protocol$hostname/"));
            $url->setBaseUri(env('base_url', '/'));

            return $url;
        });

        return $this;
    }

    /**
     * Start the session the first time a component requests the session service
     *
     * @return static
     */
    protected function registerSession()
    {
        $this->di->setShared('session', function () {
            $session = new Session();
            if (!$session->isStarted()) {
                $session->start();
            }

            return $session;
        });

        return $this;
    }

    /**
     * Register application dispatcher
     *
     * @return static
     */
    protected function registerDispatcher()
    {
        $this->di->setShared('dispatcher', function() {
            return new Dispatcher();
        });

        return $this;
    }

    /**
     * Register application request
     *
     * @return static
     */
    protected function registerRequest()
    {
        $this->di->setShared('request', function () {
            return new Request();
        });

        return $this;
    }

    /**
     * Register application response
     *
     * @return static
     */
    protected function registerResponse()
    {
        $this->di->setShared('response', function() {
            return new Response();
        });

        return $this;
    }
    
    /**
     * @return static
     */
    protected function registerRouter()
    {
        $this->di->setShared('router', function() {
            return new Router(false);
        });
        
        return $this;
    }
    
    /**
     * @return static
     */
    protected function registerDebugger()
    {
        $allowDebug = env('APP_DEBUG', false);
        if ($allowDebug) {
            new \Whoops\Provider\Phalcon\WhoopsServiceProvider(di());
        }
        
        return $this;
    }
    
    protected function registerDb()
    {
        $this->di->setShared('db', function() {
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
        
        return $this;
    }
    
    protected function registerView()
    {
        $this->di->setShared('view', function() {
            return new View();
        });
        
        return $this;
    }
}