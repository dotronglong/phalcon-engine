<?php namespace Engine\Db\Model;

trait HasTimestamp
{
    public function beforeCreate()
    {
        if (property_exists($this, 'useTimestamp') && $this->useTimestamp) {
            $now = date('Y-m-d H:i:s');
            $this->created_at = $now;
            $this->updated_at = $now;
        }
    }

    public function beforeUpdate()
    {
        if (property_exists($this, 'useTimestamp') && $this->useTimestamp) {
            $this->updated_at = date('Y-m-d H:i:s');
        }
    }
}