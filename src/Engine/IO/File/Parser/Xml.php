<?php namespace Engine\IO\File\Parser;

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