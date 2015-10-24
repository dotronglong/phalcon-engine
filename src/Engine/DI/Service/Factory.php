<?php namespace Engine\DI\Service;

use Engine\Exception\BindingResolutionException;
use Phalcon\Di\Service;
use Phalcon\DiInterface as DI;
use ReflectionClass;
use Closure;

class Factory extends Service implements Contract
{
    public function resolve($parameters = [], DI $di = null)
    {
        if (is_null($di)) {
            $di = di();
        }

        $instance   = null;
        $definition = $this->getDefinition();
        if ($definition instanceof Closure) {
            $instance = $this->resolveClosure($parameters);
        } else {
            $reflector  = $this->getReflector();
            $parameters = $this->buildDependencies($di, $reflector, $parameters);

            if (count($parameters)) {
                $instance = $reflector->newInstanceArgs(array_values($parameters));
            } else {
                $instance = $reflector->newInstance();
            }
        }
        
        if ($instance) {
            $this->_resolved = true;
        }
        
        return $instance;
    }
    
    protected function resolveClosure($parameters)
    {
        if (is_array($parameters) && count($parameters)) {
            return call_user_func_array($this->getDefinition(), $parameters);
        } else {
            return call_user_func($this->getDefinition());
        }
    }
    
    /**
     * Get Reflection Class
     * 
     * @return ReflectionClass
     * @throw \Engine\Exception\ClassNotFoundException
     */
    protected function getReflector()
    {
        $reflector = new ReflectionClass($this->getDefinition());

        // If the type is not instantiable, the developer is attempting to resolve
        // an abstract type such as an Interface of Abstract Class and there is
        // no binding registered for the abstractions so we need to bail out.
        if (!$reflector->isInstantiable())
        {
            $message = "Target [{$this->getName()}] is not instantiable.";

            throw new BindingResolutionException($message);
        }

        return $reflector;
    }
    
    /**
     * Build dependencies for a class' method
     * 
     * @param DI
     * @param ReflectionClass $reflector
     * @param array $parameters
     * @param string $name method name
     * @return array
     */
    protected function buildDependencies(DI $di, ReflectionClass $reflector, $parameters = [], $name = '__construct')
    {
        $dependencies = [];
        if ($reflector->hasMethod($name)) {
            $method = $reflector->getMethod($name);
            foreach ($method->getParameters() as $parameter) {
                // Get parameter name
                $name = $parameter->getName();

                $dependency = null;
                // Use the predefined parameter if it is set already
                if (is_array($parameters) && isset($parameters[$name])) {
                    $dependency = $parameters[$name];
                } else {
                    // Build dependency for this parameter
                    if ($abstract = $parameter->getClass()) {
                        $dependency = $di->get($abstract->getName());
                    } elseif ($parameter->isArray()) {
                        $dependency = [];
                    }

                    if ($parameter->isDefaultValueAvailable()) {
                        $dependency = $parameter->getDefaultValue();
                    }
                }

                // Add to dependencies
                $dependencies[$name] = $dependency;
            }
        }
        
        return $dependencies;
    }
}