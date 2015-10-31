<?php namespace Engine\Tests\Db;

use Engine\Db\Model\Factory as Model;
use Engine\Db\Model\HasPresenter;

class SamplePresenter
{
    public function getName()
    {
        return 'my_name';
    }
}

class SampleModel extends Model
{
    use HasPresenter;

    protected $usePresenter = true;

    protected $usePresenterClass = SamplePresenter::class;
}