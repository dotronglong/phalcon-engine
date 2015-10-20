<?php namespace Engine\DI;

use Phalcon\DI;
use Engine\DI\Service\Factory as Service;

class Container extends DI implements Contract
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

    public function get($name, $parameters = [])
    {
        // Because the DI only resolve a dependency only when it is registered,
        // so we try to bind it with itself
        if (!$this->has($name)) {
            $this->set($name, $name);
        }
        
        $service  = $this->getService($name);

        // Once this is a shared service, container will return the shared instance
        // if it was instantiated
        if ($service->isShared()) {
            if (isset($this->sharedInstances[$name]) && !is_null($this->sharedInstances[$name])) {
                return $this->sharedInstances[$name];
            }
        }

        // If this abstract has already been resolved, there is no need to resolve again
        // This case is only working for non-parameters
        if ($service->isResolved()) {
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
        if (!isset($this->providers[$name])) {
            $this->providers[$name] = null;
        }
    }

    public function getProviders()
    {
        if (count($this->providers)) {
            foreach ($this->providers as $name => $provider) {
                if (is_null($provider)) {
                    $this->providers[$name] = new $name;
                }
            }
        }
        
        return $this->providers;
    }

    public function removeProvider($name)
    {
        if (isset($this->providers[$name])) {
            unset($this->providers[$name]);
        }
    }

    public function removeProviders()
    {
        $this->setProviders([]);
    }

    public function setProviders($providers = array())
    {
        if (is_array($providers)) {
            foreach ($providers as $name) {
                $this->addProvider($name);
            }
        }
    }
}