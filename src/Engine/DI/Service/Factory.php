<?php namespace Engine\DI\Service;

use Phalcon\Di\Service;
use Phalcon\DiInterface;
use ReflectionClass;
use Closure;

class Factory extends Service implements Contract
{
    public function resolve($parameters = null, DiInterface $di = null)
    {
        if ($this->getName() == 'App\Modules\Core\Dep') {
            echo 'bingo';die;
        }
        if (is_null($di)) {
            $di = di();
        }
        
        if ($this->isShared()) {
            if (!is_null($this->_sharedInstance)) {
                return $this->_sharedInstance;
            }
        }
        
        $instance   = null;
        $definition = $this->getDefinition();
        if ($definition instanceof Closure) {
            $instance = $this->resolveClosure($parameters);
        } else {
            $reflector    = $this->getReflector();
            $dependencies = $this->buildDependencies($di, $reflector);
            dd($dependencies);
            if (count($dependencies)) {
                $instance = $reflector->newInstanceArgs($dependencies);
            } else {
                $instance = $reflector->newInstance();
            }
        }
        
        if ($instance) {
            if ($this->isShared()) {
                $this->_sharedInstance = $instance;
            }
            
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
        try {
            return new ReflectionClass($this->getDefinition());
        } catch (ReflectionException $e) {
            throw new ClassNotFoundException("Class {$this->getName()} could not be found.");
        }
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
                    var_dump($abstract->getName());die;
                    dd($di->get($abstract->getName()));
                    
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