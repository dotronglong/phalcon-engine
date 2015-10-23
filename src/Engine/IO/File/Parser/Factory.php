<?php namespace Engine\IO\File\Parser;

class Factory implements Contract
{
    use HasParser;
    
    public function isValid()
    {
        return true;
    }

    public function parse($content)
    {
        $this->setRawContent($content);
        $this->setContent($content);
        
        return $content;
    }

}