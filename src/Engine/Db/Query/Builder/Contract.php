<?php namespace Engine\Db\Query\Builder;

use Phalcon\Di\InjectionAwareInterface;
use Phalcon\Mvc\Model\Query\BuilderInterface;

interface Contract extends BuilderInterface, InjectionAwareInterface
{
    public function getPaginator();
}