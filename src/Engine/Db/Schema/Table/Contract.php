<?php namespace Engine\Db\Schema\Table;

use Engine\Db\Schema\Column\Contract as Column;

interface Contract
{
    const OPTION_IF_EXISTS     = 1;
    const OPTION_IF_NOT_EXISTS = 2;
    const OPTION_CHARSET       = 3;
    const OPTION_COLLATION     = 4;

    /**
     * Set table name
     *
     * @param string $name
     * @return static
     */
    public function setName($name);

    /**
     * Return table name
     *
     * @return string
     */
    public function getName();

    /**
     * Set table option
     *
     * @param $key
     * @param $value
     * @return static
     */
    public function setOption($key, $value);

    /**
     * Get table option
     *
     * @param      $key
     * @param null $default
     * @return mixed
     */
    public function getOption($key, $default = null);

    /**
     * Remove an option
     *
     * @param $key
     * @return static
     */
    public function removeOption($key);

    /**
     * Add column
     *
     * @param int        $type
     * @param            $name
     * @param array|null $options
     * @param null       $default
     * @param bool|false $nullable
     * @return Column
     */
    public function addColumn($type, $name, $options = null, $default = null, $nullable = false);

    /**
     * Remove column
     *
     * @param $name
     * @return void
     */
    public function removeColumn($name);

    /**
     * Drop table
     *
     * @param bool|true $ifExists
     * @return static
     */
    public function drop($ifExists = true);

    /**
     * Return generated SQL string
     *
     * @return string
     */
    public function toSql();
}