<?php namespace Engine\Tests\Db;

use Engine\Db\Model\Factory as Model;

class SampleModelWithoutPresenter extends Model
{
    protected $usePresenter = false;

    public function getSource()
    {
        return 'sample_table';
    }
}