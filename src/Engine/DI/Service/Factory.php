<?php namespace Engine\DI\Service;

use Engine\Exception\BindingResolutionException;
use Phalcon\Di\Service;
use Phalcon\DiInterface;
use ReflectionClass;
use Closure;

class Factory extends Service implements Contract
{
    public function resolve($parameters = null, DiInterface $di = null)
    {
        if (is_null($di)) {
            $di = di();
        }

        $instance   = null;
        $definition = $this->getDefinition();
        if ($definition instanceof Closure) {
            $instance = $this->resolveClosure($parameters);
        } else {
            $reflector = $this->getReflector();

            // We build the dependencies
            $dependencies = $this->buildDependencies($di, $reflector);
            if (count($dependencies)) {
                $instance = $reflector->newInstanceArgs($dependencies);
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
     * @param ReflectionClass $reflector
     * @param string $name method name
     * @return array
     */
    protected function buildDependencies(DiInterface $di, ReflectionClass $reflector, $name = '__construct')
    {
        $dependencies = [];
        if ($reflector->hasMethod($name)) {
            $method = $reflector->getMethod($name);
            foreach ($method->getParameters() as $parameter) {
                $dependency = null;
                if ($abstract = $parameter->getClass()) {
                    $dependency = $di->get($abstract->getName());
                } elseif ($parameter->isArray()) {
                    $dependency = [];
                }

                if ($parameter->isDefaultValueAvailable()) {
                    $dependency = $parameter->getDefaultValue();
                }

                $dependencies[] = $dependency;
            }
        }
        
        return $dependencies;
    }
}