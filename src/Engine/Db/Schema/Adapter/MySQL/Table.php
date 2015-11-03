<?php namespace Engine\Db\Schema\Adapter\MySQL;

use Engine\Db\Schema\Column\Contract as Column;
use Engine\Db\Schema\Table\Contract;
use Engine\Db\Schema\Table\Factory;

class Table extends Factory implements Contract
{
    private $sql;

    public function addColumn($type, $name, $options = null, $default = null, $nullable = false)
    {
        // TODO: Implement addColumn() method.
    }

    public function toSql()
    {
        // TODO: Implement toSql() method.
        if (!empty($this->sql)) {
            return $this->sql;
        }
    }

    public function drop($ifExists = true)
    {
        // TODO: Implement drop() method.
        $this->sql = 'DROP TABLE' . ($ifExists ? ' IF EXISTS ' : ' ') . $this->getName();
    }

    /**
     * Add integer column
     *
     * @param string $name
     * @param int    $size
     * @return Column
     */
    public function integer($name, $size = null)
    {

    }

    /**
     * Add integer column
     *
     * @param string $name
     * @param int    $size
     * @return Column
     */
    public function int($name, $size = 11)
    {

    }

    /**
     * Add small integer column
     *
     * @param string $name
     * @param int    $size
     * @return Column
     */
    public function tinyInt($name, $size = 4)
    {

    }

    /**
     * Add small integer column
     *
     * @param string $name
     * @param int    $size
     * @return Column
     */
    public function smallInt($name, $size = 6)
    {

    }

    /**
     * Add medium integer column
     *
     * @param string $name
     * @param int    $size
     * @return Column
     */
    public function mediumInt($name, $size = 9)
    {

    }

    /**
     * Add big integer column
     *
     * @param string $name
     * @param int    $size
     * @return Column
     */
    public function bigInt($name, $size = 20)
    {

    }
}