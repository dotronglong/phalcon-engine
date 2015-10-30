<?php namespace Engine\Resolver;

use Engine\Exception\FunctionNotCallableException;

class Factory implements Contract
{
    /**
     * @var array
     */
    protected $resolvers = [];

    public function get($name, \Closure $default = null)
    {
        // TODO: Implement get() method.
        return isset($this->resolvers[$name]) ? $this->resolvers[$name] : $default;
    }

    public function set($name, \Closure $resolver)
    {
        // TODO: Implement set() method.
        $this->resolvers[$name] = $resolver;
        return $this;
    }

    public function run($name, \Closure $default = null, $parameters = [])
    {
        // TODO: Implement run() method.
        $resolver = $this->get($name, $default);
        if (is_callable($resolver)) {
            return call_user_func_array($resolver, $parameters);
        } else {
            throw new FunctionNotCallableException('Resolver is not callable');
        }
    }

}