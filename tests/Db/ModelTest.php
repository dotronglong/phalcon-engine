<?php namespace Engine\Tests\Db;

use Phalcon\Mvc\Model\Manager as ModelsManager;
use Engine\Tests\TestCase;
use Engine\Db\Model\Factory as Model;
use Engine\Db\Model\Contract as ModelContract;
use Phalcon\Events\Manager as EventsManager;

class ModelTest extends TestCase
{
    public function testImplementContract()
    {
        $modelsManager = new ModelsManager();
        di()->setShared('modelsManager', $modelsManager);

        $model = new SampleModel();
        $this->assertInstanceOf(ModelContract::class, $model);
        return $model;
    }

    /**
     * @depends testImplementContract
     */
    public function testGetTable(Model $model)
    {
        $model = clone($model);
        $this->assertEquals('sample_model', $model->getTable());
    }

    /**
     * @depends testImplementContract
     */
    public function testModelWithPresenter(Model $model)
    {
        $model = clone($model);
        $this->assertEquals('my_name', $model->getName());
    }

    public function testModelWithoutPresenter()
    {
        $this->assertException('Phalcon\Mvc\Model\Exception', function() {
            $model = new SampleModelWithoutPresenter();
            return $model->getName();
        });
    }
}