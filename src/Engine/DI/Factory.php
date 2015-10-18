<?php namespace Engine\DI;

use Phalcon\DI;
use Engine\Engine;

class Factory extends DI implements Contract
{

    public function registerServiceProviders()
    {
        // TODO: Implement registerServiceProviders() method.
        $providers = config('app.providers');
        if (count($providers)) {
            foreach ($providers as $className) {
                $provider = Engine::newInstance($className);
                if ($provider instanceof ServiceProvider) {
                    $provider->boot();
                }
            }
        }
    }

    public function runServiceProviders()
    {
        // TODO: Implement runServiceProviders() method.
    }
}