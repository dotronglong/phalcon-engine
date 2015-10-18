<?php namespace Engine;

use Phalcon\Mvc\Application;
use Engine\Shared\HasSingleton;
use Engine\DI\Factory as DI;

class Bootstrap
{
    use HasSingleton;

    public static function run()
    {
        di(new DI());

        self::getInstance()
            ->setupEnv()
            ->setupConfig()
            ->setupContainer();

        $application = new Application(di());
        $application->handle();
        //dd($application);
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
        di()->registerServiceProviders();
    }
}
