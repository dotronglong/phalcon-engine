<?php namespace Engine\DI;

use Phalcon\DiInterface as DI;

trait HasInjection
{
    /**
     * @var DI 
     */
    protected $di;
    
    /**
     * Sets the dependency injector
     */
    public function setDI(DI $di)
    {
        $this->di = $di;
    }
    
    public function getDI()
    {
        return $this->di;
    }
}