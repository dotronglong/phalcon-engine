<?php namespace Engine\Tests\DI;

use Phalcon\DiInterface;
use Phalcon\Di\ServiceInterface;
use Engine\Tests\TestCase;

require 'SampleObject.php';

class InjectionTest extends TestCase
{
    protected $abstractClass = 'abstract_class';
    protected $definitionClass = SampleObject::class;
    protected $firstObject = FirstObject::class;
    protected $secondObject = SecondObject::class;
    
    public function testImplementContract()
    {
        $di = di();
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
        $this->assertInstanceOf($this->firstObject, $instance->getFirstObject());
        $this->assertInstanceOf($this->secondObject, $instance->getSecondObject());
    }

    /**
     * @depends testImplementContract
     */
    public function testResolveObject($di)
    {
        $di = clone($di);
        $object = new SecondObject();
        $object->content = 'my_content';
        $di->set('sample', $object);
        $instance = $di->get('sample');
        $this->assertInstanceOf($this->secondObject, $instance);
        $this->assertEquals('my_content', $instance->content);
    }

    /**
     * @depends testSetService
     */
    public function testResolveAbstractWithParameters($di)
    {
        $content = 'this_is_test_content';
        $secondObject = new SecondObject();
        $secondObject->content = $content;
        $instance = $di->get($this->abstractClass, ['secondObject' => $secondObject]);
        $this->assertEquals($content, $instance->getSecondObject()->content);
    }

    /**
     * @depends testImplementContract
     */
    public function testSetSharedService($di)
    {
        $service = $di->setShared($this->abstractClass, $this->definitionClass);
        $this->assertInstanceOf(ServiceInterface::class, $service);
        $this->assertTrue($service->isShared());
        return $di;
    }

    /**
     * @depends testSetSharedService
     */
    public function testShareService($di)
    {
        $this->assertInstanceOf($this->definitionClass, $di->getShared($this->abstractClass));
    }
}