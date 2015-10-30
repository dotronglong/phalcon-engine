<?php namespace Engine\Dispatcher;

use Engine\Exception\InvalidInstanceException;
use Phalcon\Di\InjectionAwareInterface;
use Phalcon\Mvc\Dispatcher;
use Engine\Event\HasEventsManager;
use Engine\Http\HasRequest;
use Engine\DI\Service\Factory as Service;
use Phalcon\Events\Manager as EventsManager;
use Engine\Routing\Router\Contract as RouterContract;

class Factory extends Dispatcher implements Contract
{
    use HasRequest, HasEventsManager;

    /**
     * @var int
     */
    public static $maxDispatches = 256;

    /**
     * Preparing for dispatching
     *
     * @return void
     */
    protected function preDispatch()
    {
        $router     = $this->request->getRouter();
        $resolver   = $this->getDI()->get('resolver');

        $this->setControllerName($resolver->run('dispatch:controller', function() use ($router) {
            $moduleName = $router->getModuleName();
            $ctlName    = ucfirst($router->getControllerName());
            return "\\App\\Modules\\$moduleName\\Controllers\\$ctlName";
        }, [$router, $this]));

        $this->setActionName($resolver->run('dispatch:action', function() use ($router) {
            return $router->getActionName();
        }));

        $this->setParams($router->getParams());
    }

    public function dispatch()
    {
        // Get EventsManager
        $em = $this->getEventsManager();

        // Call event beforeDispatchLoop
        if (is_object($em)) {
            if ($em->fire('dispatch:beforeDispatchLoop', $this) === false) {
                return false;
            }
        }

        $wasFresh = false;
        $numberDispatches = 0;

        // Dispatch loop
        $this->_finished = false;
        while (!$this->_finished) {
            $numberDispatches++;
            if ($numberDispatches === static::$maxDispatches) {
                $this->{"_throwDispatchException"}("Dispatcher has detected a cyclic routing causing stability problems", self::EXCEPTION_CYCLIC_ROUTING);
				break;
            }

            $this->_finished = true;

            // Run pre-dispatch
            $this->preDispatch();

            $controller = $this->getHandlerClass();
            $action     = $this->getActiveMethod();
            $parameters = $this->getParams();

            if ($this->fireEventBeforeAction('dispatch:beforeDispatch', $em) === false) {
                continue;
            }

            // Instantiating the controller class via DI Factory
            $handler = $this->getDI()->get($controller);
            if ($this->getDI()->wasFreshInstance()) {
                $wasFresh = true;
            }
            if ($handler instanceof InjectionAwareInterface) {
                $handler->setDI($this->getDI());
            } else {
                throw new InvalidInstanceException("$controller must implement " . InjectionAwareInterface::class);
            }
            $this->_activeHandler = $handler;

            if (!method_exists($handler, $action)) {
                if ($this->fireEventBeforeAction('dispatch:beforeNotFoundAction', $em) === false) {
                    continue;
                }

                $status = $this->{"_throwDispatchException"}("Action '" . $action . "' was not found on handler '" . $controller . "'", self::EXCEPTION_ACTION_NOT_FOUND);
				if ($status === false) {
                    if ($this->_finished === false) {
                        continue;
                    }
				}

				break;
            }

            // beforeExecuteRoute
            if ($this->fireEventBeforeAction('dispatch:beforeExecuteRoute', $em) === false) {
                continue;
            }
            if (method_exists($handler, 'beforeExecuteRoute')) {
                if ($handler->beforeExecuteRoute($this) === false) {
                    continue;
                }
                if ($this->_finished === false) {
                    continue;
                }
            }

            // Call the 'initialize' method just once per request
            if ($wasFresh === true) {
                if (method_exists($handler, 'initialize')) {
                    $handler->initialize();
                }

                if ($this->fireEventBeforeAction('dispatch:afterInitialize', $em) === false) {
                    continue;
                }
            }

            try {
                // Execute the action with Method Dependency Injection
                $this->_returnedValue = Service::resolveMethod($handler, $action, $parameters);
            } catch (\Exception $e) {
                if ($this->{"_handleException"}($e) === false) {
                    if ($this->_finished === false) {
                        continue;
                    }
				} else {
                    throw $e;
                }
            }

            if ($this->fireEventBeforeAction('dispatch:afterExecuteRoute', $em) === false) {
                continue;
            }
            if (is_object($em)) {
                $em->fire('dispatch:afterDispatch', $this);
            }

            if (method_exists($handler, 'afterExecuteRoute')) {
                if ($handler->afterExecuteRoute($this, $this->_returnedValue) === false) {
                    continue;
                }
                if ($this->_finished === false) {
                    continue;
                }
            }
        }

        if (is_object($em)) {
            $em->fire('dispatch:afterDispatchLoop', $this);
        }

        return $this->getReturnedValue();
    }

    protected function fireEventBeforeAction($event, EventsManager $em = null)
    {
        if (is_object($em)) {
            if ($em->fire($event, $this) === false) {
                return false;
            }

            if ($this->_finished === false) {
                return false;
            }
        }

        return true;
    }

    public function forward($forward)
    {
        if (!is_array($forward)) {
            $this->{"_throwDispatchException"}("Forward parameter must be an Array");
			return null;
        }

        $current = $this->request->getRouter();
        $router  = $this->getDI()->get(RouterContract::class);
        $router->setModuleName(isset($forward['module']) ? $forward['module'] : $current->getModuleName())
               ->setControllerName(isset($forward['controller']) ? $forward['controller'] : $current->getControllerName())
               ->setActionName(isset($forward['action']) ? $forward['action'] : $current->getActionName())
               ->setParams(isset($forward['params']) ? $forward['params'] : []);

        $this->request->setRouter($router);
        $this->_finished = false;
        $this->_forwared = true;
    }
}