<?php namespace Engine\DI;

use Engine\Exception\InvalidInstanceException;
use Phalcon\DI;
use Engine\DI\Service\Factory as Service;
use Engine\Engine;

class Factory extends DI implements Contract
{
    /**
     * Registers
     * 
     * @var array
     */
    protected $registers = [];

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
            //$this->set($name, $name);
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

    public function addRegister($name)
    {
        // TODO: Implement addRegister() method.
        if (!$this->hasRegister($name)) {
            $this->registers[] = $name;
        }

        return $this;
    }

    public function getRegisters()
    {
        // TODO: Implement getRegisters() method.
        return is_null($this->registers) || !is_array($this->registers) ? [] : $this->registers;
    }

    public function hasRegister($name)
    {
        // TODO: Implement hasRegister() method.
        return in_array($name, $this->registers);
    }

    public function removeRegister($name)
    {
        // TODO: Implement removeRegister() method.
        foreach ($this->getRegisters() as $i => $register) {
            if (is_string($register) && $register === $name) {
                unset($this->registers[$i]);
                break;
            }
        }

        return $this;
    }

    public function removeRegisters()
    {
        // TODO: Implement removeRegisters() method.
        $this->registers = [];

        return $this;
    }

    public function setRegisters($registers = array())
    {
        // TODO: Implement setRegisters() method.
        if (is_array($registers)) {
            foreach ($registers as $name) {
                $this->addRegister($name);
            }
        }

        return $this;
    }

    public function makeRegisters()
    {
        // TODO: Implement makeRegisters() method.
        if (count($this->registers)) {
            $di = di();
            foreach ($this->registers as $i => $name) {
                if (is_string($name)) {
                    $register = Engine::newInstance($name);
                    if ($register instanceof ServiceRegister) {
                        $register->setDI($di);
                        $this->registers[$i] = $register;
                    } else {
                        throw new InvalidInstanceException("$name must implement " . ServiceRegister::class);
                    }
                }
            }
        }

        return $this;
    }
}