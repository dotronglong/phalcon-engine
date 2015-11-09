<?php namespace Engine\DI\Service;

use Engine\Exception\BindingResolutionException;
use Engine\Exception\ClassNotFoundException;
use Engine\Exception\InvalidInstanceException;
use Engine\Exception\MethodNotFoundException;
use Phalcon\Di\Service;
use Phalcon\DiInterface as DI;
use ReflectionClass;
use Closure;
use Exception;

class Factory extends Service implements Contract
{
    public function resolve($parameters = [], DI $di = null)
    {
        $instance   = null;
        $definition = $this->getDefinition();
        if ($definition instanceof Closure) {
            $instance = $this->resolveClosure($parameters);
        } elseif (is_object($definition)) {
            $instance = $definition;
        } elseif (is_string($definition)) {
            $instance = $this->resolveInstance($definition, $parameters);
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
    protected static function getReflector($name)
    {
        try {
            $reflector = new ReflectionClass($name);
        } catch (Exception $e) {
            throw new ClassNotFoundException("Class $name could not be found");
        }

        // If the type is not instantiable, the developer is attempting to resolve
        // an abstract type such as an Interface of Abstract Class and there is
        // no binding registered for the abstractions so we need to bail out.
        if (!$reflector->isInstantiable())
        {
            $message = "Target [$name] is not instantiable.";

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
    protected static function buildDependencies(DI $di, ReflectionClass $reflector, $parameters = [], $name = '__construct')
    {
        $dependencies = [];
        if ($reflector->hasMethod($name)) {
            $method = $reflector->getMethod($name);
            foreach ($method->getParameters() as $i => $parameter) {
                // Get parameter name
                $name = $parameter->getName();

                $dependency = null;
                if (is_array($parameters) && isset($parameters[$name])) {
                    // Use the predefined parameter if it is set already
                    $dependency = $parameters[$name];
                } elseif (is_array($parameters) && isset($parameters[$i]) && !is_null($parameter->getClass())) {
                    // Use the provided parameter if it is set and instanceof the parameter abstract
                    $typeHint = $parameter->getClass()->getName();
                    if ($parameters[$i] instanceof $typeHint) {
                        $dependency = $parameters[$i];
                    } else {
                        throw new InvalidInstanceException("$typeHint is required, provided: " . get_class($parameters[$i]));
                    }
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

    public static function resolveMethod($object, $method, $parameters = null)
    {
        // TODO: Implement resolveMethod() method.
        $di = di();
        if (is_object($object)) {
            $objectClass = get_class($object);
        } else {
            $objectClass = $object;
            $object      = $di->get($objectClass);
        }

        if (!method_exists($object, $method)) {
            throw new MethodNotFoundException("$method could not be found in $objectClass");
        }

        $reflector   = new ReflectionClass($objectClass);
        $parameters  = self::buildDependencies($di, $reflector, $parameters, $method);

        return call_user_func_array([$object, $method], $parameters);
    }

    public static function resolveInstance($name, $parameters = null)
    {
        // TODO: Implement resolveInstance() method.
        $reflector  = self::getReflector($name);
        $parameters = self::buildDependencies(di(), $reflector, $parameters);

        if (count($parameters)) {
            $instance = $reflector->newInstanceArgs(array_values($parameters));
        } else {
            $instance = $reflector->newInstance();
        }

        return $instance;
    }
}