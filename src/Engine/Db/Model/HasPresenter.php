<?php namespace Engine\Db\Model;

use Engine\Db\Model\Presenter\Contract as Presenter;

trait HasPresenter
{
    /**
     * Use Presenter or not
     *
     * @var bool
     */
    protected $usePresenter = true;

    /**
     * @var string
     */
    protected $usePresenterClass;

    /**
     * @var Presenter
     */
    protected $presenter;

    public function __call($name, $arguments)
    {
        if ($this->usePresenter) {
            if (is_null($this->presenter)) {
                if (is_null($this->usePresenterClass)) {
                    $args  = explode('\\', get_called_class());
                    array_pop($args);
                    $usePresenterClass = join('\\', $args) . '\\Presenter';
                } else {
                    $usePresenterClass = $this->usePresenterClass;
                }
                $presenter = di($usePresenterClass);
                if ($presenter instanceof Presenter) {
                    $presenter->setResource($this);
                }
            }

            if (method_exists($presenter, $name)) {
                return call_user_func_array([$presenter, $name], $arguments);
            }
        }

        return parent::__call($name, $arguments);
    }
}