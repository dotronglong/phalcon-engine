<?php namespace Engine\Tests\Db;

use Phalcon\Mvc\Model\Manager as ModelsManager;
use Engine\Tests\TestCase;
use Engine\Db\Model\Factory as Model;
use Engine\Db\Model\Contract as ModelContract;

class ModelTest extends TestCase
{
    public function testImplementContract()
    {
        $di = di();
        $modelsManager = new ModelsManager();
        $di->setShared('modelsManager', $modelsManager);
        $model = new Model($di);
        $this->assertInstanceOf(ModelContract::class, $model);
        return $model;
    }

    /**
     * @depends testImplementContract
     */
    public function testGetTable(Model $model)
    {
        $this->assertEquals('factory', $model->getTable());
    }
}