<?php namespace Engine\DI;

use Phalcon\DI;
use Engine\Engine;

class Factory extends DI implements Contract
{
    /**
     * @var array
     */
    protected $providers = [];

    public function registerServiceProviders()
    {
        // TODO: Implement registerServiceProviders() method.
        $providers = config('app.providers');
        if (count($providers)) {
            foreach ($providers as $className) {
                $provider = Engine::newInstance($className);
                if ($provider instanceof ServiceProvider) {
                    $this->providers[] = $provider;
                    $provider->boot();
                }
            }
        }
    }

    public function runServiceProviders()
    {
        // TODO: Implement runServiceProviders() method.
        if (count($this->providers)) {
            foreach ($this->providers as $provider) {
                $provider->ready();
            }
        }
    }

    public function get($abstract, $parameters = [])
    {

    }

    protected function make(HasDependency $abstract, $parameters = [])
    {

    }
}