<?php namespace Engine\Tests\DI;

use Engine\Application\Container\Contract as ContainerContract;
use Engine\Application\Container\Factory as Container;
use Engine\Tests\TestCase;
use Engine\DI\ServiceRegister;
use Engine\Exception\ClassNotFoundException;

class ContainerTest extends TestCase
{
    protected $testRegister     = 'my_register';
    protected $validRegister    = SampleServiceRegister::class;

    public function testImplementContract()
    {
        $container = new Container();
        $this->assertInstanceOf(ContainerContract::class, $container);
        return $container;
    }

    /**
     * @depends testImplementContract
     */
    public function testAddAndRemoveRegister($container)
    {
        $container = clone($container);
        $container->addRegister($this->testRegister);
        $this->assertCount(1, $container->getRegisters());
        $container->removeRegister($this->testRegister);
        $this->assertCount(0, $container->getRegisters());
    }

    /**
     * @depends testImplementContract
     */
    public function testGetRegisters($container)
    {
        $container = clone($container);
        $this->assertCount(0, $container->getRegisters());
        $container->addRegister($this->testRegister);
        $this->assertCount(1, $container->getRegisters());
    }

    /**
     * @depends testImplementContract
     */
    public function testSetAndRemoveRegisters($container)
    {
        $container = clone($container);
        $container->setRegisters([$this->testRegister]);
        $this->assertCount(1, $container->getRegisters());
        $container->removeRegisters();
        $this->assertCount(0, $container->getRegisters());
    }

    /**
     * @depends testImplementContract
     */
    public function testMakeRegisters($container)
    {
        $container = clone($container);
        $container->setRegisters([$this->testRegister]);
        $this->assertException(ClassNotFoundException::class, function() use ($container) {
            $container->makeRegisters();
        });
        $container->removeRegisters();

        $container->addRegister($this->validRegister);
        $registers = $container->makeRegisters()->getRegisters();
        $this->assertArrayInstanceOf(ServiceRegister::class, $registers);
    }
}