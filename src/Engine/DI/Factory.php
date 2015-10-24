<?php namespace Engine\DI;

use Engine\Exception\InvalidInstanceException;
use Phalcon\DI;
use Engine\DI\Service\Factory as Service;
use Engine\Engine;

class Factory extends DI implements Contract
{
    /**
     * Providers
     * 
     * @var array
     */
    protected $providers = [];

    /**
     * Resolved Instances
     *
     * @var array
     */
    protected $resolvedInstances = [];

    /**
     * Shared Instances
     *
     * @var array
     */
    protected $sharedInstances = [];

    public function get($name, $parameters = null)
    {
        // Because the DI only resolve a dependency only when it is registered,
        // so we try to bind it with itself
        if (!$this->has($name)) {
            $this->set($name, $name);
        }

        // Retrieve the appropriate service
        $service  = $this->getService($name);

        // Once this is a shared service, container will return the shared instance
        // if it was instantiated
        if ($service->isShared()) {
            if (isset($this->sharedInstances[$name]) && !is_null($this->sharedInstances[$name])) {
                return $this->sharedInstances[$name];
            }
        }

        // If this abstract has already been resolved, there is no need to resolve again
        if ($service->isResolved() && is_null($parameters)) {
            if (isset($this->resolvedInstances[$name]) && !is_null($this->resolvedInstances[$name])) {
                return clone($this->resolvedInstances[$name]);
            }
        }

        // Try to resolve with dependency injection
        $instance = parent::get($name, $parameters);

        // Make it as sharable item if sharing is enabled
        if ($service->isShared()) {
            $this->sharedInstances[$name] = $instance;
        }

        // Mark it as resolved item for improving performance later
        if ($service->isResolved()) {
            $this->resolvedInstances[$name] = clone($instance);
        }

        return $instance;
    }
    
    public function attempt($name, $definition, $shared = false)
    {
        if (!isset($this->_services[$name])) {
            return $this->set($name, $definition, $shared);
        }
        
        return false;
    }
    
    public function set($name, $definition, $shared = false)
    {
        $service = new Service($name, $definition, $shared);
        $this->_services[$name] = $service;
        return $service;
    }
    
    public function setShared($name, $definition)
    {
        return $this->set($name, $definition, true);
    }

    public function addProvider($name)
    {
        // TODO: Implement addProvider() method.
        if (!isset($this->providers[$name])) {
            $this->providers[$name] = null;
        }
    }

    public function getProviders()
    {
        // TODO: Implement getProviders() method.
        return $this->providers;
    }

    public function hasProvider($name)
    {
        // TODO: Implement hasProvider() method.
        return array_key_exists($name, $this->providers);
    }

    public function removeProvider($name)
    {
        // TODO: Implement removeProvider() method.
        if ($this->hasProvider($name)) {
            unset($this->providers[$name]);
        }
    }

    public function removeProviders()
    {
        // TODO: Implement removeProviders() method.
        $this->providers = [];
    }

    public function setProviders($providers = array())
    {
        // TODO: Implement setProviders() method.
        if (is_array($providers)) {
            foreach ($providers as $name) {
                $this->addProvider($name);
            }
        }
    }

    public function makeProviders()
    {
        // TODO: Implement makeProviders() method.
        if (count($this->providers)) {
            foreach ($this->providers as $name => $provider) {
                if (is_null($provider)) {
                    $provider = Engine::newInstance($name);
                    if ($provider instanceof ServiceProvider) {
                        $this->providers[$name] = $provider;
                    } else {
                        throw new InvalidInstanceException("$name must implement " . ServiceProvider::class);
                    }
                }
            }
        }

        return $this->providers;
    }
}