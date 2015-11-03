<?php namespace Engine\Db\Schema\Column;

interface Contract
{
    const TYPE_INTEGER   = 1;
    const TYPE_TINYINT   = 2;
    const TYPE_SMALLINT  = 3;
    const TYPE_MEDIUMINT = 4;
    const TYPE_BIGINT    = 5;

    /**
     * Set default value. Should be an alias of default($value)
     *
     * @param $value
     * @return static
     */
    public function setDefault($value = null);

    /**
     * Allow null
     *
     * @return static
     */
    public function nullable();

    /**
     * Mark column is unsigned.
     *
     * @return static
     */
    public function unsigned();

    /**
     * Return generated SQL string
     *
     * @return string
     */
    public function toSql();
}