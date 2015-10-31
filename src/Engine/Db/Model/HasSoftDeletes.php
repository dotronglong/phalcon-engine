<?php namespace Engine\Db\Model;

trait HasSoftDeletes
{
    public function beforeDelete()
    {
        if (property_exists($this, 'useSoftDeletes') && $this->useSoftDeletes) {
            $this->deleted_at = date('Y-m-d H:i:s');
        }
    }
}