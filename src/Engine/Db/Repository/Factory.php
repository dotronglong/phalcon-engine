<?php namespace Engine\Db\Repository;

use Engine\Db\Contract as Db;
use Engine\Db\Model\Contract as Model;
use Phalcon\Mvc\Model\ManagerInterface as ModelsManager;

class Factory implements Contract
{
    /**
     * @var Model
     */
    protected $model;

    /**
     * @var ModelsManager
     */
    protected $modelsManager;

    public function setModelsManager(ModelsManager $modelsManager)
    {
        // TODO: Implement setModelsManager() method.
        $this->modelsManager = $modelsManager;
    }

    public function getModelsManager()
    {
        // TODO: Implement getModelsManager() method.
        return $this->modelsManager;
    }

    public function setModel(Model $model)
    {
        // TODO: Implement setModel() method.
        $this->model = $model;
    }

    public function getModel()
    {
        // TODO: Implement getModel() method.
        return $this->model;
    }

    public function query()
    {
        // TODO: Implement query() method.
        return Db::newQuery($this->getModel());
    }

}