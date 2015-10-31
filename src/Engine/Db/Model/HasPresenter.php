<?php namespace Engine\Db\Model;

use Engine\Db\Model\Presenter\Contract as Presenter;

trait HasPresenter
{
    /**
     * @var Presenter
     */
    protected $presenter;

    public function __call($name, $arguments)
    {
        if (property_exists($this, 'usePresenter') && $this->usePresenter) {
            if (is_null($this->presenter)) {
                if (property_exists($this, 'usePresenterClass') && is_null($this->usePresenterClass)) {
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