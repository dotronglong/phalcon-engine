<?php namespace Engine\Io\File\Parser;

interface Contract
{    
    /**
     * Parse content
     * 
     * @param string $content
     * @return mixed
     */
    public function parse($content);
    
    /**
     * Whether content is valid to be parsed
     * 
     * @param string $content
     * @return bool
     */
    public function isValid($content);
}