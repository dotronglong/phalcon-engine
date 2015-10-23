<?php namespace Engine\Url;

use Engine\DI\Contract as DI;
use Engine\DI\ServiceProvider as ServiceProviderContract;
use Phalcon\Mvc\Url;

class ServiceProvider implements ServiceProviderContract
{

    public function boot(DI $di)
    {
        // TODO: Implement boot() method.
        $di->setShared('url', function () {
            $protocol = stripos(server('SERVER_PROTOCOL'), 'https') === true ? 'https://' : 'http://';
            $hostname = server('HTTP_HOST');

            $url = new Url();
            $url->setStaticBaseUri(env('static_url', "$protocol$hostname/"));
            $url->setBaseUri(env('base_url', '/'));

            return $url;
        });
    }

    public function ready()
    {
        // TODO: Implement ready() method.
    }
}