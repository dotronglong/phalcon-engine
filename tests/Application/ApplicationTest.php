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

        return $app;
    }


    public function testHandle()
    {
        $app = $this->setUp();
        $resolver = di('resolver');
        $resolver->set('dispatch:controller', function($router) {
            return '\\Engine\\Tests\\Application\\Sample';
        });

        $sources = [
            '/blog/add'  => [
                'action'    => 'add',
                'response'  => 'test'
            ],
            '/blog/view' => [
                'action'    => 'view',
                'exception' => 'Phalcon\\Mvc\\Dispatcher\\Exception'
            ]
        ];
        $router = di('router');
        foreach ($sources as $uri => $source) {
            $router->add($uri, "Blog::Index::{$source['action']}");
            try {
                $response = $app->handle($uri);
                $this->assertEquals($source['response'], $response);
            } catch (\Exception $e) {
                if (isset($source['exception'])) {
                    $this->assertInstanceOf($source['exception'], $e);
                }
            }
        }
    }
}