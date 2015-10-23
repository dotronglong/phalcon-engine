<?php namespace Engine\Http;

use Engine\Http\Request\Contract as Request;

trait HasRequest
{
    /**
     * @var Request
     */
    protected $request;

    public function setRequest(Request $request)
    {
        $this->request = $request;
    }

    public function getRequest()
    {
        return $this->request;
    }
}