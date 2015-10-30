<?php namespace Engine\Resolver;

use Engine\Exception\FunctionNotCallableException;

interface Contract
{
    /**
     * Get Resolver by name
     *
     * @param string $name
     * @param \Closure|null $default
     * @return mixed
     */
    public function get($name, \Closure $default = null);

    /**
     * Set Resolver
     *
     * @param  string  $name
     * @param \Closure $resolver
     * @return static
     */
    public function set($name, \Closure $resolver);

    /**
     * Run a resolver
     *
     * @param  string       $name
     * @param \Closure|null $default
     * @param  array        $parameters
     * @return mixed
     *
     * @throws FunctionNotCallableException
     */
    public function run($name, \Closure $default = null, $parameters = []);
}