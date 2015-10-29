<?php namespace Engine\Url;

use Engine\DI\ServiceProvider as ServiceProviderContract;
use Phalcon\Mvc\Url;
use Engine\DI\HasInjection;

class ServiceProvider implements ServiceProviderContract
{
    use HasInjection;

    public function boot()
    {
        // TODO: Implement boot() method.
        $this->getDI()->setShared('url', function () {
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