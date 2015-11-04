<?php namespace Engine\Application;

use Engine\DI\ServiceRegister as ServiceRegisterContract;
use Engine\DI\HasInjection;

class ServiceRegister implements ServiceRegisterContract
{
    use HasInjection;

    public function onBoot()
    {
        // TODO: Implement onBoot() method.
        $this->registerDotEnv();
    }

    public function onReady()
    {
        // TODO: Implement onReady() method.
    }

    protected function registerDotEnv()
    {
        if (defined('PATH_FILE_ENV')) {
            $path = PATH_FILE_ENV;
        } else {
            $path = __DIR__ . '/../../../config/.env';
        }

        if (file_exists($path)) {
            $content = file_get_contents($path);
            if (!empty($content)) {
                $lines = explode(PHP_EOL, $content);
                if (count($lines)) {
                    foreach ($lines as $line) {
                        $line = str_replace("\r", '', $line);
                        if (empty($line) || !preg_match('/(.*)\=(.*)/i', $line)) continue;
                        putenv($line);
                    }
                }
            }
        }
    }
}