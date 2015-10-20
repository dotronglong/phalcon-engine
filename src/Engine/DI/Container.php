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