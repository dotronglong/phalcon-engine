<?php namespace Engine\Url;

use Engine\DI\ServiceRegister as ServiceRegisterContract;
use Phalcon\Mvc\Url;
use Engine\DI\HasInjection;

class ServiceRegister implements ServiceRegisterContract
{
    use HasInjection;

    public function onBoot()
    {
        // TODO: Implement onBoot() method.
        $this->getDI()->setShared('url', function () {
            $protocol = stripos(server('SERVER_PROTOCOL'), 'https') === true ? 'https://' : 'http://';
            $hostname = server('HTTP_HOST');

            $url = new Url();
            $url->setStaticBaseUri(env('static_url', "$protocol$hostname/"));
            $url->setBaseUri(env('base_url', '/'));

            return $url;
        });
    }

    public function onReady()
    {
        // TODO: Implement onReady() method.
    }
}