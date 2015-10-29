<?php namespace Engine\Tests\DI;

use Engine\DI\Contract as DiContract;
use Engine\DI\Factory as DI;
use Engine\Tests\TestCase;
use Engine\DI\ServiceRegister;
use Engine\Exception\ClassNotFoundException;

class ContainerTest extends TestCase
{
    protected $testRegister     = 'my_register';
    protected $validRegister    = 'Engine\Application\ServiceRegister';

    public function testImplementContract()
    {
        $di = new DI();
        $this->assertInstanceOf(DiContract::class, $di);
        return $di;
    }

    /**
     * @depends testImplementContract
     */
    public function testAddRegister($di)
    {
        $di->addRegister($this->testRegister);
        $this->assertTrue($di->hasRegister($this->testRegister));
    }

    /**
     * @depends testImplementContract
     */
    public function testRemoveRegister($di)
    {
        $di->removeRegister($this->testRegister);
        $this->assertCount(0, $di->getRegisters());
    }

    /**
     * @depends testImplementContract
     * @depends testRemoveRegister
     */
    public function testGetRegisters($di)
    {
        $this->assertCount(0, $di->getRegisters());
        $di->addRegister($this->testRegister);
        $this->assertCount(1, $di->getRegisters());
    }

    /**
     * @depends testImplementContract
     */
    public function testRemoveRegisters($di)
    {
        $di->removeRegisters();
        $this->assertCount(0, $di->getRegisters());
    }

    /**
     * @depends testImplementContract
     */
    public function testSetRegisters($di)
    {
        $di->setRegisters([$this->testRegister]);
        $this->assertCount(1, $di->getRegisters());
    }

    /**
     * @depends testImplementContract
     */
    public function testMakeRegisters($di)
    {
        $this->assertException(ClassNotFoundException::class, function() use ($di) {
            $di->makeRegisters();
        });
        $di->removeRegisters();

        $di->addRegister($this->validRegister);
        $registers = $di->makeRegisters();
        $this->assertArrayInstanceOf(ServiceRegister::class, $registers);
    }
}