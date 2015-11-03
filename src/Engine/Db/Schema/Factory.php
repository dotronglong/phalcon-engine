<?php namespace Engine\Db\Schema;

use Closure;
use Phalcon\Db\AdapterInterface;
use Engine\Db\Schema\Table\Contract as Table;

class Factory implements Contract
{
    /**
     * @var AdapterInterface
     */
    protected $adapter;

    public function setApdater(AdapterInterface $adapter)
    {
        // TODO: Implement setApdater() method.
        $this->adapter = $adapter;
        return $this;
    }

    public function getAdapter()
    {
        // TODO: Implement getAdapter() method.
        return $this->adapter;
    }

    /**
     * @return Table
     */
    protected function getTable()
    {
        $adapter = $this->getAdapter();
        $type    = $adapter->getType();
        return di("db.schema.$type.table");
    }

    public function create($name, Closure $callback, $ifNotExists = true)
    {
        // TODO: Implement create() method.
        $table = $this->getTable();
        $table->setName($name)
              ->setOption(Table::OPTION_IF_NOT_EXISTS, $ifNotExists);

        call_user_func_array($callback, [$table]);
        return $this->getAdapter()->execute($table->toSql());
    }

    public function drop($name, $ifExists = true)
    {
        // TODO: Implement drop() method.
        $table = $this->getTable()->setName($name)->drop($ifExists);
        return $this->getAdapter()->execute($table->toSql());
    }
}