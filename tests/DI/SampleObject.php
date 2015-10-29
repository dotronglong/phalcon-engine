<?php namespace Engine\Tests\DI;

class FirstObject
{
    
}

class SecondObject
{
    public $content = '';
}

class SampleObject
{
    protected $firstObject;
    protected $secondObject;
    
    public function __construct(FirstObject $firstObject, SecondObject $secondObject)
    {
        $this->firstObject  = $firstObject;
        $this->secondObject = $secondObject;
    }
    
    public function sampleMethod()
    {
        
    }
    
    public function getFirstObject()
    {
        return $this->firstObject;
    }
    
    public function getSecondObject()
    {
        return $this->secondObject;
    }
}