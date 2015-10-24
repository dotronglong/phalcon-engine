<?php namespace Engine\Db\Repository;

use Phalcon\Di\InjectionAwareInterface;
use Phalcon\Mvc\Model\ManagerInterface as ModelsManager;
use Engine\Db\Query\Builder\Contract as QueryBuilder;
use Engine\Db\Model\Contract as Model;

interface Contract extends InjectionAwareInterface
{
    /**
     * Set Models Manager
     *
     * @param ModelsManager $modelsManager
     * @return void
     */
    public function setModelsManager(ModelsManager $modelsManager);

    /**
     * Get Models Manager
     *
     * @return ModelsManager
     */
    public function getModelsManager();

    /**
     * Get a new instance of Builder which is suitable for Model
     *
     * @return QueryBuilder
     */
    public function getBuilder();

    /**
     * Set Model
     *
     * @param Model $model
     * @return void
     */
    public function setModel(Model $model);

    /**
     * Get Model
     *
     * @return Model
     */
    public function getModel();
}