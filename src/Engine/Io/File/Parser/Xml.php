<?php namespace Engine\Io\File\Parser;

class Xml implements Contract
{
    public function isValid($content)
    {
        return true;
    }

    public function parse($content)
    {
    }

}