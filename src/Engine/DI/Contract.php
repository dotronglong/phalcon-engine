<?php namespace Engine\DI;

use Engine\Exception\ClassNotFoundException;
use Engine\Exception\InvalidInstanceException;
use Phalcon\DiInterface;

interface Contract extends DiInterface
{
    /**
     * Add a provider by name
     * 
     * @param string $name
     * @return void
     */
    public function addProvider($name);
    
    /**
     * Remove a provider by name
     * 
     * @param string $name
     * @return void
     */
    public function removeProvider($name);

    /**
     * Check if provider's name has been added
     *
     * @param string $name
     * @return bool
     */
    public function hasProvider($name);
    
    /**
     * Get all providers
     * 
     * @return array
     */
    public function getProviders();
    
    /**
     * Set providers
     * 
     * @param array $providers
     * @return void
     */
    public function setProviders($providers = []);
    
    /**
     * Remove all providers
     * 
     * @return void
     */
    public function removeProviders();

    /**
     * Create all instances of providers
     *
     * @return ServiceProvider[]
     * @throws ClassNotFoundException
     * @throws InvalidInstanceException
     */
    public function makeProviders();
}