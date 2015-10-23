<?php namespace Engine\Tests\DI;

use Phalcon\DiInterface;
use Phalcon\Di\ServiceInterface;
use Engine\DI\Container as DI;
use Engine\Tests\TestCase;
use Engine\DI\ServiceProvider;
use Engine\Exception\ClassNotFoundException;

class ContainerTest extends TestCase
{
    protected $abstractClass    = 'Engine\Contracts\Config';
    protected $definitionClass  = 'Engine\Config';
    protected $testProvider     = 'my_provider';
    protected $validProvider    = 'Engine\Application\ServiceProvider';

    public function testImplementContract()
    {
        $di = new DI();
        $this->assertInstanceOf(DiInterface::class, $di);
        return $di;
    }

    /**
     * @depends testImplementContract
     */
    public function testSetService($di)
    {
        $service = $di->set($this->abstractClass, $this->definitionClass);
        $this->assertInstanceOf(ServiceInterface::class, $service);
        return $di;
    }

    /**
     * @depends testSetService
     */
    public function testResolveAbstract($di)
    {
        $instance = $di->get($this->abstractClass);
        $this->assertInstanceOf($this->definitionClass, $instance);
    }

    /**
     * @depends testImplementContract
     */
    public function testSetSharedService($di)
    {
        $service = $di->setShared($this->abstractClass, $this->definitionClass);
        $this->assertInstanceOf(ServiceInterface::class, $service);
        return $di;
    }

    /**
     * @depends testSetSharedService
     */
    public function testShareService($di)
    {
        $this->assertInstanceOf($this->definitionClass, $di->getShared($this->abstractClass));
    }

    /**
     * @depends testImplementContract
     */
    public function testAddProvider($di)
    {
        $di->addProvider($this->testProvider);
        $this->assertTrue($di->hasProvider($this->testProvider));
    }

    /**
     * @depends testImplementContract
     */
    public function testRemoveProvider($di)
    {
        $di->removeProvider($this->testProvider);
        $this->assertCount(0, $di->getProviders());
    }

    /**
     * @depends testImplementContract
     * @depends testRemoveProvider
     */
    public function testGetProviders($di)
    {
        $this->assertCount(0, $di->getProviders());
        $di->addProvider($this->testProvider);
        $this->assertCount(1, $di->getProviders());
    }

    /**
     * @depends testImplementContract
     */
    public function testRemoveProviders($di)
    {
        $di->removeProviders();
        $this->assertCount(0, $di->getProviders());
    }

    /**
     * @depends testImplementContract
     */
    public function testSetProviders($di)
    {
        $di->setProviders([$this->testProvider]);
        $this->assertCount(1, $di->getProviders());
    }

    /**
     * @depends testImplementContract
     */
    public function testMakeProviders($di)
    {
        $this->assertException(ClassNotFoundException::class, function() use ($di) {
            $di->makeProviders();
        });
        $di->removeProviders();

        $di->addProvider($this->validProvider);
        $providers = $di->makeProviders();
        $this->assertArrayInstanceOf(ServiceProvider::class, $providers);
    }
}