<?php namespace Engine\DI;

use Phalcon\Di;
use Phalcon\Di\InjectionAwareInterface;
use Phalcon\Events\EventsAwareInterface;
use Phalcon\DiInterface;
use Phalcon\Di\ServiceInterface;
use Engine\Exception\InvalidInstanceException;
use Engine\DI\Service\Factory as Service;
use Engine\Engine;
use Engine\Event\HasEventsManager;
use Exception;

class Factory implements Contract
{
    use HasEventsManager;

    /**
     * @var Service[]
     */
    protected $services = [];

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

    /**
     * Try to resolve as a simple way
     *
     * @param string $name
     * @param array $parameters
     * @return mixed
     */
    protected function resolve($name, $parameters = null)
    {
        if (isset($this->resolvedInstances[$name]) && is_null($parameters)) {
            return clone($this->resolvedInstances[$name]);
        }

        $instance = Engine::newInstance($name, $parameters);
        $instance = $this->applyAwareInterface($instance);
        if (is_object($instance)) {
            $this->resolvedInstances[$name] = $instance;
        }

        $this->_freshInstance = true;
        return $instance;
    }

    public function get($name, $parameters = null)
    {
        // Because the DI only resolve a dependency only when it is registered,
        // so we try to resolve it in simple way if it is not registered
        if (!$this->has($name)) {
            return $this->resolve($name, $parameters);
        }

        // Retrieve the appropriate service
        $service  = $this->getService($name);

        // Get EventsManager
        $eventsManager = $this->getEventsManager();

        // Call event beforeServiceResolve
        if (is_object($eventsManager)) {
            $eventsManager->fire('di:beforeServiceResolve', $this, ['name' => $name, 'parameters' => $parameters]);
        }

        // Once this is a shared service, container will return the shared instance
        // if it was instantiated
        if ($service->isShared()) {
            if (isset($this->sharedInstances[$name]) && !is_null($this->sharedInstances[$name])) {
                $this->_freshInstance = false;
                return $this->sharedInstances[$name];
            }
        }

        // If this abstract has already been resolved, there is no need to resolve again
        if ($service->isResolved() && is_null($parameters)) {
            if (isset($this->resolvedInstances[$name]) && !is_null($this->resolvedInstances[$name])) {
                $this->_freshInstance = true;
                return clone($this->resolvedInstances[$name]);
            }
        }

        // Try to resolve with dependency injection
        $instance = $service->resolve($parameters, $this);
        $instance = $this->applyAwareInterface($instance);

        // Make it as sharable item if sharing is enabled
        if ($service->isShared()) {
            $this->sharedInstances[$name] = $instance;
        }

        // Mark it as resolved item for improving performance later
        if ($service->isResolved()) {
            $this->resolvedInstances[$name] = clone($instance);
        }

        // Call event afterServiceResolve
        if (is_object($eventsManager)) {
            $eventsManager->fire('di:afterServiceResolve', $this, [
                'name'       => $name,
                'parameters' => $parameters,
                'instance'   => $instance
            ]);
        }

        $this->_freshInstance = true;
        return $instance;
    }

    public static function applyAwareInterface($instance)
    {
        $di = di();
        if ($instance instanceof InjectionAwareInterface) {
            $instance->setDI($di);
        }
        if ($instance instanceof EventsAwareInterface) {
            $instance->setEventsManager($di->getEventsManager());
        }

        return $instance;
    }
    
    public function attempt($name, $definition, $shared = false)
    {
        if (!isset($this->services[$name])) {
            return $this->set($name, $definition, $shared);
        }
        
        return false;
    }
    
    public function set($name, $definition, $shared = false)
    {
        $service = new Service($name, $definition, $shared);
        $this->services[$name] = $service;
        return $service;
    }
    
    public function setShared($name, $definition)
    {
        return $this->set($name, $definition, true);
    }

    public function getShared($name, $parameters = null)
    {
        if (isset($this->sharedInstances[$name])) {
            $this->_freshInstance = false;
            return $this->sharedInstances[$name];
        } else {
            return $this->get($name, $parameters);
        }
    }

    public function remove($name)
    {
        if (isset($this->services[$name])) {
            unset($this->services[$name]);
        }
        if (isset($this->sharedInstances[$name])) {
            unset($this->sharedInstances[$name]);
        }
        if (isset($this->resolvedInstances[$name])) {
            unset($this->resolvedInstances[$name]);
        }
    }

    public function getService($name)
    {
        return isset($this->services[$name]) ? $this->services[$name] : null;
    }

    public function has($name)
    {
        return isset($this->services[$name]);
    }

    public function wasFreshInstance()
    {
        return $this->_freshInstance;
    }

    public function getServices()
    {
        return $this->services;
    }

    public static function setDefault(DiInterface $di)
    {
        Di::setDefault($di);
    }

    /**
     * @return DiInterface
     */
    public static function getDefault()
    {
        return Di::getDefault();
    }

    public function setRaw($name, ServiceInterface $definition)
    {
        $this->services[$name] = $definition;
        return $definition;
    }

    public function getRaw($name)
    {
        if (isset($this->services[$name])) {
            return $this->services[$name]->getDefinition();
        }

        throw new Exception("Service '" . $name . "' wasn't found in the dependency injection container");
    }

    public static function reset()
    {
        Di::reset();
    }

    /**
     * Check if a service is registered using the array syntax
     */
    public function offsetExists($name)
	{
		return $this->has($name);
	}

    /**
     * Allows to register a shared service using the array syntax
     *
     *<code>
     *	$di["request"] = new \Phalcon\Http\Request();
     *</code>
     *
     * @param string $name
     * @param mixed $definition
     * @return boolean
     */
    public function offsetSet($name, $definition)
	{
		$this->setShared($name, $definition);
		return true;
	}

    /**
     * Allows to obtain a shared service using the array syntax
     *
     *<code>
     *	var_dump($di["request"]);
     *</code>
     *
     * @param string name
     * @return mixed
     */
    public function offsetGet($name)
	{
		return $this->getShared($name);
	}

    /**
     * Removes a service from the services container using the array syntax
     */
    public function offsetUnset($name)
	{
		return $this->remove($name);
	}

    /**
     * Magic method to get or set services using setters/getters
     *
     * @param string $name
     * @param array  $arguments
     * @return mixed
     */
    public function __call($name, $arguments = null)
    {
        if (strpos($name, 'get') === 0) {
            $name = substr($name, 3);
            return $this->get($name, $arguments);
        }
        if (strpos($name, 'set') === 0) {
            $name = substr($name, 3);
            $definition = isset($arguments[0]) ? $arguments[0] : null;
            $shared = isset($arguments[1]) ? (bool) $arguments[1] : false;
            return $this->set($name, $definition, $shared);
        }

        /**
         * The method doesn't start with set/get throw an exception
         */
        throw new Exception("Call to undefined method or service '" . $name . "'");
    }
}