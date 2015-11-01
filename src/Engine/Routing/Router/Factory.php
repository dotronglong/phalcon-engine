<?php namespace Engine\Routing\Router;

use Phalcon\Mvc\Router;

class Factory extends Router implements Contract
{

    public function setControllerName($ctlName)
    {
        // TODO: Implement setControllerName() method.
        $this->_controller = $ctlName;
        return $this;
    }

    public function setActionName($actionName)
    {
        // TODO: Implement setActionName() method.
        $this->_action = $actionName;
        return $this;
    }

    public function setModuleName($moduleName)
    {
        // TODO: Implement setModuleName() method.
        $this->_module = $moduleName;
        return $this;
    }

    public function setParams(array $params = [])
    {
        // TODO: Implement setParams() method.
        $this->_params = $params;
        return $this;
    }

    public function setNamespaceName($namespaceName)
    {
        // TODO: Implement setNamespaceName() method.
        $this->namespace = $namespaceName;
        return $this;
    }

}