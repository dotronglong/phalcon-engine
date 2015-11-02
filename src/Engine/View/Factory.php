<?php namespace Engine\View;

use Phalcon\Mvc\View\Simple as View;

class Factory extends View implements Contract
{
    /**
     * @var string
     */
    protected $path;

    public function setPath($path)
    {
        // TODO: Implement setPath() method.
        $this->path = $path;
        return $this;
    }

    public function getPath()
    {
        // TODO: Implement getPath() method.
        return $this->path;
    }
}