<?php namespace Engine\Db\Schema;

use Closure;
use Engine\Db\Schema\Table\Contract as Table;
use Phalcon\Db\AdapterInterface;

interface Contract
{
    /**
     * Set Adapter
     *
     * @param AdapterInterface $adapter
     * @return static
     */
    public function setApdater(AdapterInterface $adapter);

    /**
     * Get Adapter
     *
     * @return AdapterInterface
     */
    public function getAdapter();

    /**
     * Create a table
     *
     * @param string    $name
     * @param Closure   $callback
     * @param bool|true $ifNotExists
     * @return boolean
     */
    public function create($name, Closure $callback, $ifNotExists = true);

    /**
     * Drop table if exists
     *
     * @param           $name
     * @param bool|true $ifExists
     * @return boolean
     */
    public function drop($name, $ifExists = true);
}