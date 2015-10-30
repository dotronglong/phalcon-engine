<?php namespace Engine\Tests\Application;

use Phalcon\Mvc\Controller;

class SampleController extends Controller
{
    public function addAction()
    {
        return 'test';
    }
}