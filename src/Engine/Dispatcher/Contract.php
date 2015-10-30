<?php namespace Engine\Dispatcher;

use Phalcon\Mvc\DispatcherInterface;
use Phalcon\Events\EventsAwareInterface;
use Engine\Http\Request\Contract as Request;

interface Contract extends DispatcherInterface, EventsAwareInterface
{
    /**
     * Set Request to dispatcher
     *
     * @param Request $request
     * @return void
     */
    public function setRequest(Request $request);

    /**
     * Get dispatcher's request
     *
     * @return Request
     */
    public function getRequest();
}