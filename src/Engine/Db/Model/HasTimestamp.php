<?php namespace Engine\Db\Model;

trait HasTimestamp
{
    /**
     * Use Timestamp or not
     *
     * @var bool
     */
    protected $useTimestamp = true;

    /**
     * @var string
     */
    protected $created_at;

    /**
     * @var string
     */
    protected $updated_at;

    public function beforeCreate()
    {
        if ($this->useTimestamp) {
            $now = date('Y-m-d H:i:s');
            $this->created_at = $now;
            $this->updated_at = $now;
        }
    }

    public function beforeUpdate()
    {
        if ($this->useTimestamp) {
            $this->updated_at = date('Y-m-d H:i:s');
        }
    }
}