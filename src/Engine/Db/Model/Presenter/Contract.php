<?php namespace Engine\Db\Model\Presenter;

use Engine\Db\Model\Contract as Resource;

interface Contract
{
    /**
     * Get Resource
     *
     * @return Resource
     */
    public function getResource();

    /**
     * Set Resource
     *
     * @param Resource $resource
     * @return void
     */
    public function setResource(Resource $resource);
}