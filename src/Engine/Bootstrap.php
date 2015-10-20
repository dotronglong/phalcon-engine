<?php namespace Engine;

use Engine\Shared\HasSingleton;
use Engine\DI\Container as DI;
use Engine\DI\ServiceProvider;
use Engine\Application\Factory as Application;

class Bootstrap
{
    use HasSingleton;

    public static function run()
    {
        // Initialize Dependency Injection Container
        di(new DI());
        
        // Run system setup
        self::getInstance()
            ->setupEnv()
            ->setupConfig()
            ->setupContainer();

        // Create application and add to DI
        $app = new Application(di());
        di()->setShared('app', $app);

        // System is ready to load
        self::getInstance()->ready();

        // Run application
        $app->handle();
    }

    /**
     * Setup Environment Variables
     *
     * @return static
     */
    protected function setupEnv()
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

        return $this;
    }

    protected function setupConfig()
    {
        di()->setShared('config', function() {
            return new Config();
        });

        return $this;
    }

    protected function setupContainer()
    {
        di()->setProviders(config('app.providers'));
        foreach (di()->getProviders() as $name => $provider) {
            if ($provider instanceof ServiceProvider) {
                $provider->boot();
            }
        }
    }
    
    protected function ready()
    {
        foreach (di()->getProviders() as $name => $provider) {
            if ($provider instanceof ServiceProvider) {
                $provider->ready();
            }
        }
    }
}
