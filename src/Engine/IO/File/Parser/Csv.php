<?php namespace Engine\IO\File\Parser;

class Csv implements Contract
{
    public function isValid($content)
    {
        return empty($content) ? false : true;
    }

    public function parse($content)
    {
        if (!$this->isValid($content)) {
            return false;
        }
        
        return array_map('str_getcsv', explode(PHP_EOL, $content));
    }
}