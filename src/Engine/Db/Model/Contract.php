<?php namespace Engine\Db\Model;

use Phalcon\Mvc\EntityInterface,
    Phalcon\Mvc\ModelInterface,
    Phalcon\Mvc\Model\ResultInterface;

interface Contract extends EntityInterface, ModelInterface, ResultInterface
{
    /**
     * Get table's name
     *
     * @see \Phalcon\Mvc\Model::getSource()
     * @return string
     */
    public function getTable();
}