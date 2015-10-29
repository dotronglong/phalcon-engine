<?php namespace Engine\DI;

use Engine\Exception\ClassNotFoundException;
use Engine\Exception\InvalidInstanceException;
use Phalcon\DiInterface;

interface Contract extends DiInterface
{
    /**
     * Add a register by name
     * 
     * @param string $name
     * @return void
     */
    public function addRegister($name);
    
    /**
     * Remove a register by name
     * 
     * @param string $name
     * @return void
     */
    public function removeRegister($name);

    /**
     * Check if register's name has been added
     *
     * @param string $name
     * @return bool
     */
    public function hasRegister($name);
    
    /**
     * Get all registers
     * 
     * @return array
     */
    public function getRegisters();
    
    /**
     * Set registers
     * 
     * @param array $registers
     * @return void
     */
    public function setRegisters($registers = []);
    
    /**
     * Remove all registers
     * 
     * @return void
     */
    public function removeRegisters();

    /**
     * Create all instances of registers
     *
     * @return static
     * @throws ClassNotFoundException
     * @throws InvalidInstanceException
     */
    public function makeRegisters();
}