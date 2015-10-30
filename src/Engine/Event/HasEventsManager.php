<?php namespace Engine\Event;

use Phalcon\Events\ManagerInterface as EventManager;

trait HasEventsManager
{
    /**
     * @var EventManager
     */
    protected $eventsManager;

    /**
     * Get EventsManager
     *
     * @return EventManager
     */
    public function getEventsManager()
    {
        return $this->eventsManager;
    }

    /**
     * Set EventsManager
     *
     * @param EventManager $eventsManager
     */
    public function setEventsManager(EventManager $eventsManager)
    {
        $this->eventsManager = $eventsManager;
    }
}