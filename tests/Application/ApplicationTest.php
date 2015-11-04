<?php namespace Engine\Tests\Application;

use Engine\Tests\TestCase;
use Engine\Application\Factory as Application;
use Phalcon\Events\Manager as EventsManager;
use Engine\Routing\Router\Factory as Router;
use Engine\Application\Container\Factory as Container;

class ApplicationTest extends TestCase
{
    protected function setUp()
    {
        $app = new Application();
        $di  = di();
        $app->setEventsManager($di->getEventsManager());

        $container = new Container();
        $container->setDI($di);
        $container->setRegisters([
            \Engine\Session\ServiceRegister::class,
            \Engine\Debug\ServiceRegister::class,
            \Engine\Resolver\ServiceRegister::class,
            \Engine\Application\ServiceRegister::class,
            \Engine\Db\ServiceRegister::class,
            \Engine\Dispatcher\ServiceRegister::class,
            \Engine\Http\ServiceRegister::class,
            \Engine\Routing\ServiceRegister::class,
            \Engine\Url\ServiceRegister::class,
            \Engine\View\ServiceRegister::class,
        ])->makeRegisters();
        foreach ($container->getRegisters() as $register) {
            $register->onBoot();
        }

        // Make sure default .env loaded
        $this->assertEquals('localhost', env('DB_HOST'));

        return $app;
    }

    protected function getDispatcherListener()
    {
        $dispatcherListener = $this->getMockBuilder('DispatcherListener')
                                   ->setMethods(['beforeDispatch'])
                                   ->getMock();

        $dispatcherListener->expects($this->once())
                           ->method('beforeDispatch')
                           ->will($this->returnValue('\\Engine\\Tests\\Application\\'));

        return $dispatcherListener;
    }

    public function testHandle()
    {
        $app = $this->setUp();
        $resolver = di('resolver');
        $resolver->set('dispatch:controller', function() {
            return '\\Engine\\Tests\\Application\\Sample';
        });
        $resolver->set('dispatch:forward', function($controller, $module) {
            return '\\Engine\\Tests\\Application\\Another';
        });

        $sources = [
            '/blog/add'  => [
                'action'    => 'add',
                'response'  => 'test'
            ],
            '/blog/view' => [
                'action'    => 'view',
                'exception' => 'Phalcon\\Mvc\\Dispatcher\\Exception'
            ],
            '/blog/forward' => [
                'action'    => 'forward',
                'response'  => 'my_action'
            ]
        ];
        $router = di('router');
        foreach ($sources as $uri => $source) {
            $router->add($uri, "Blog::Index::{$source['action']}");
            try {
                $app->handle($uri);
                $response = di('dispatcher')->getReturnedValue();
                $this->assertEquals($source['response'], $response);
            } catch (\Exception $e) {
                if (isset($source['exception'])) {
                    $this->assertInstanceOf($source['exception'], $e);
                } else {
                    echo $e->getMessage();
                    dd($e->getTraceAsString());
                }
            }
        }
    }
}