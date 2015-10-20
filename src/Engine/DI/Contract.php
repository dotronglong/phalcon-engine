<?php namespace Engine\DI;

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
     * Unregister a provider by name
     * 
     * @param string $name
     * @return void
     */
    public function removeProvider($name);
    
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
}