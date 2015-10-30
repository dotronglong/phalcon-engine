<?php namespace Engine\Db\Model;

use Phalcon\Mvc\Model;

class Factory extends Model implements Contract
{
    use HasPresenter, HasTimestamp, HasSoftDeletes;

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden;

    /**
     * The attributes that should be visible in arrays.
     *
     * @var array
     */
    protected $visible;

    final public function initialize()
    {
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

    public function toArray($columns = null)
    {
        if (is_null($columns) && is_array($this->visible)) {
            // Only show the visible columns
            $columns = $this->visible;
        }

        $attributes = parent::toArray($columns);

        if (is_null($columns) && is_array($this->hidden) && count($attributes)) {
            // Only show columns which have not been hidden
            foreach($attributes as $key => $value) {
                if (in_array($key, $this->hidden)) {
                    unset($attributes[$key]);
                }
            }
        }

        return $attributes;
    }
}