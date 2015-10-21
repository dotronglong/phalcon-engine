<?php namespace Engine;

use Engine\DI\Container as DI;
use Engine\DI\ServiceProvider;
use Engine\Application\Factory as Application;
use Engine\Exception\ClassNotFoundException;
use Engine\Exception\NullPointerException;
use ReflectionClass;
use ReflectionException;

final class Engine
{
    /**
     * Create new instance
     *
     * @return mixed
     */
    public static function newInstance()
    {
        $args = func_get_args();
        if (count($args) === 0) {
            throw new NullPointerException('Class name must be defined!');
        }

        $className = $args[0];
        unset($args[0]);

        try {
            $rc = new ReflectionClass($className);
            if (count($args)) {
                return $rc->newInstanceArgs($args);
            } else {
                return $rc->newInstance();
            }
        } catch (ReflectionException $e) {
            throw new ClassNotFoundException("Class $className could not be found.");
        }
    }

    public static function run()
    {
        // Initialize Dependency Injection Container
        $di = new DI();
        di($di);
        
        // Run system setup
        self::setupEnv();
        self::setupConfig();
        self::setupContainer();

        // Create application and add to DI
        $app = self::setupApplication();

        // System is ready to load
        self::ready($app);
    }

    /**
     * Setup Environment Variables
     */
    protected static function setupEnv()
    {
        $env = PATH_ROOT . '/.env';
        if (file_exists($env)) {
            $content = file_get_contents($env);
            if (!empty($content)) {
                $lines = explode(PHP_EOL, $content);
                if (count($lines)) {
                    foreach ($lines as $line) {
                        if (empty($line)) continue;
                        putenv($line);
                    }
                }
            }
        }
    }

    protected static function setupConfig()
    {
        di()->setShared('config', function() {
            return new Config();
        });
    }

    protected static function setupContainer()
    {
        $di = di();
        $di->setProviders(config('app.providers'));
        foreach ($di->getProviders() as $name => $provider) {
            if ($provider instanceof ServiceProvider) {
                $provider->boot($di);
            }
        }
    }
    
    protected static function setupApplication()
    {
        $di  = di();
        $app = new Application($di);
        $di->setShared('app', $app);
        return $app;
    }
    
    protected static function ready(Application $app)
    {
        foreach (di()->getProviders() as $name => $provider) {
            if ($provider instanceof ServiceProvider) {
                $provider->ready();
            }
        }
        
        $app->handle();
    }
}