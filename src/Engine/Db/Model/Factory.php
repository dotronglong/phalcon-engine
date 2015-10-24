<?php namespace Engine\Db\Model;

use Engine\Db\Query\Contract as Query;
use Phalcon\Mvc\Model;
use Phalcon\Mvc\Model\Behavior\Timestampable;
use Phalcon\Mvc\Model\Behavior\SoftDelete;

class Factory extends Model implements Contract
{
    /**
     * Use Timestamp or not
     *
     * @var bool
     */
    protected $useTimestamp = true;

    /**
     * Use SoftDeletes or not
     *
     * @var bool
     */
    protected $useSoftDeletes = true;

    final public function initialize()
    {
        if ($this->useTimestamp) {
            $this->addBehavior(new Timestampable([
                'onCreate' => [
                    'field'  => [
                        'created_at',
                        'updated_at'
                    ],
                    'format' => 'Y-m-d H:i:s'
                ],
                'onUpdate' => [
                    'field'  => 'updated_at',
                    'format' => 'Y-m-d H:i:s'
                ]
            ]));
        }

        if ($this->useSoftDeletes) {
            $this->addBehavior(new SoftDelete([
                'field' => 'deleted_at',
                'value' => date('Y-m-d H:i:s')
            ]));
        }

        // Set default connection service
        $this->setConnectionService('db');

        // Call customize model's boot
        $this->boot();
    }

    /**
     * Boot up Model. Run one time only.
     *
     * @return void
     */
    protected function boot()
    {
        // TODO: Define relationships and other actions
    }

    public function getTable()
    {
        // TODO: Implement getTable() method.
        return $this->getSource();
    }
}