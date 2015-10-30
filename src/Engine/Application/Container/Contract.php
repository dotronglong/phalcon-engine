<?php namespace Engine\Application\Container;

use Engine\Exception\ClassNotFoundException;
use Engine\Exception\InvalidInstanceException;
use Phalcon\Di\InjectionAwareInterface;
use Phalcon\Events\EventsAwareInterface;

interface Contract extends InjectionAwareInterface, EventsAwareInterface
{
    /**
     * Add a register by name
     *
     * @param string $name
     * @return static
     */
    public function addRegister($name);

    /**
     * Remove a register by name
     *
     * @param string $name
     * @return static
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
     * @return static
     */
    public function setRegisters($registers = []);

    /**
     * Remove all registers
     *
     * @return static
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