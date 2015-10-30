<?php namespace Engine\Tests\Db;

use Engine\Db\Model\Factory as Model;

class SamplePresenter
{
    public function getName()
    {
        return 'my_name';
    }
}

class SampleModel extends Model
{
    protected $usePresenterClass = SamplePresenter::class;
}