<?php namespace Engine\Routing\Router;

use Phalcon\Mvc\RouterInterface;

interface Contract extends RouterInterface
{
    /**
     * Set Controller's name
     *
     * @param string $ctlName
     * @return static
     */
    public function setControllerName($ctlName);

    /**
     * Set Action's name
     *
     * @param string $actionName
     * @return static
     */
    public function setActionName($actionName);

    /**
     * Set Module's name
     *
     * @param string $moduleName
     * @return static
     */
    public function setModuleName($moduleName);

    /**
     * Set Parameters
     *
     * @param array $params
     * @return static
     */
    public function setParams(array $params = []);

    /**
     * Set Namespace's name
     *
     * @param $namespaceName
     * @return static
     */
    public function setNamespaceName($namespaceName);
}