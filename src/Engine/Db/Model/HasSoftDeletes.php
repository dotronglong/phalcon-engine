<?php namespace Engine\Db\Model;

trait HasSoftDeletes
{
    /**
     * Use SoftDeletes or not
     *
     * @var bool
     */
    protected $useSoftDeletes = true;

    /**
     * @var string
     */
    protected $deleted_at;

    public function beforeDelete()
    {
        if ($this->useSoftDeletes) {
            $this->deleted_at = date('Y-m-d H:i:s');
        }
    }
}