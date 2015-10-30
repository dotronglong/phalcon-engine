<?php namespace Engine\Tests\Application;

use Engine\Tests\TestCase;
use Engine\Application\Factory as Application;
use Engine\DI\Factory as DI;
use Phalcon\Events\Manager as EventsManager;

class ApplicationTest extends TestCase
{
    protected function setUp()
    {
        $app = new Application();
        $di  = new DI();
        $em  = new EventsManager();

        $app->setEventsManager($em);
        $di->setInternalEventsManager($em);
        $di->setShared('eventsManager', $em);

        $di->setRegisters([
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
        foreach ($di->getRegisters() as $register) {
            $register->onBoot();
        }

        return $app;
    }


    public function testHandle()
    {
//        $app = $this->setUp();
//        $uri = '/blog/add';
//
//        $response = $app->handle($uri);
//        dd($response);
    }
}