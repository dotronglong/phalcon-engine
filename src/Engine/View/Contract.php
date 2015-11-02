<?php namespace Engine\View;

use Phalcon\Mvc\ViewBaseInterface as Base;

interface Contract extends Base
{
    /**
     * Set render path
     *
     * @param $path
     * @return static
     */
    public function setPath($path);

    /**
     * Get render path
     *
     * @return string
     */
    public function getPath();
}