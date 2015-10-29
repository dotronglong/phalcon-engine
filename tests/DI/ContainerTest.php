<?php namespace Engine\Tests\DI;

use Engine\DI\Contract as DiContract;
use Engine\DI\Factory as DI;
use Engine\Tests\TestCase;
use Engine\DI\ServiceRegister;
use Engine\Exception\ClassNotFoundException;

class ContainerTest extends TestCase
{
    protected $testRegister     = 'my_register';
    protected $validRegister    = SampleServiceRegister::class;

    public function testImplementContract()
    {
        $di = new DI();
        $this->assertInstanceOf(DiContract::class, $di);
        return $di;
    }

    /**
     * @depends testImplementContract
     */
    public function testAddAndRemoveRegister($di)
    {
        $di = clone($di);
        $di->addRegister($this->testRegister);
        $this->assertCount(1, $di->getRegisters());
        $di->removeRegister($this->testRegister);
        $this->assertCount(0, $di->getRegisters());
    }

    /**
     * @depends testImplementContract
     */
    public function testGetRegisters($di)
    {
        $di = clone($di);
        $this->assertCount(0, $di->getRegisters());
        $di->addRegister($this->testRegister);
        $this->assertCount(1, $di->getRegisters());
    }

    /**
     * @depends testImplementContract
     */
    public function testSetAndRemoveRegisters($di)
    {
        $di = clone($di);
        $di->setRegisters([$this->testRegister]);
        $this->assertCount(1, $di->getRegisters());
        $di->removeRegisters();
        $this->assertCount(0, $di->getRegisters());
    }

    /**
     * @depends testImplementContract
     */
    public function testMakeRegisters($di)
    {
        $di = clone($di);
        $di->setRegisters([$this->testRegister]);
        $this->assertException(ClassNotFoundException::class, function() use ($di) {
            $di->makeRegisters();
        });
        $di->removeRegisters();

        $di->addRegister($this->validRegister);
        $registers = $di->makeRegisters()->getRegisters();
        $this->assertArrayInstanceOf(ServiceRegister::class, $registers);
    }
}