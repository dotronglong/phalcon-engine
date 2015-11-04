<?php

use Phalcon\Di as DI;
use Engine\Debug\Dumper;
use Engine\Helper\Str;
use Engine\Config\Contract as Config;
use Engine\View\Contract as ViewContract;
use Engine\Resolver\Contract as Resolver;

if (! function_exists('d')) {
    /**
     * Dump the passed variable
     *
     * @param mixed $o
     * @param bool $d die if TRUE
     * @return void
     */
    function d($o, $d = false)
    {
        echo '<pre>' . print_r($o, true) . '</pre>';
        if ($d) die(1);
    }
}

if (! function_exists('dd')) {
    /**
     * Dump the passed variables and end the script.
     *
     * @param  mixed
     * @return void
     */
    function dd()
    {
        array_map(function ($x) {
            (new Dumper)->dump($x);
        }, func_get_args());

        die(1);
    }
}

if (! function_exists('base_path')) {
    /**
     * Return the appropriate base path
     *
     * @param string $path
     * @return string
     */
    function base_path($path = null)
    {
        return (defined('PATH_ROOT') ? PATH_ROOT : '') . $path;
    }
}

if (! function_exists('app_path')) {
    /**
     * Return the appropriate app path
     *
     * @param string $path
     * @return string
     */
    function app_path($path = null)
    {
        return (defined('PATH_APP') ? PATH_APP : '') . $path;
    }
}

if ( ! function_exists('value'))
{
    /**
     * Return the default value of the given value.
     *
     * @param  mixed  $value
     * @return mixed
     */
    function value($value)
    {
        return $value instanceof Closure ? $value() : $value;
    }
}

if ( ! function_exists('env'))
{
    /**
     * Gets the value of an environment variable. Supports boolean, empty and null.
     *
     * @param  string  $key
     * @param  mixed   $default
     * @return mixed
     */
    function env($key, $default = null)
    {
        $value = getenv($key);

        if ($value === false) return value($default);

        switch (strtolower($value)) {
            case 'true':
            case '(true)':
                return true;

            case 'false':
            case '(false)':
                return false;

            case 'empty':
            case '(empty)':
                return '';

            case 'null':
            case '(null)':
                return;
        }

        if (Str::startsWith($value, '"') && Str::endsWith($value, '"')) {
            return substr($value, 1, -1);
        }

        return $value;
    }
}

if (! function_exists('di')) {
    /**
     * Get the available container instance.
     *
     * @param  string  $make
     * @param  array   $parameters
     * @return mixed|\Engine\DI\Factory
     */
    function di($make = null, $parameters = [])
    {
        if (is_null($make)) {
            return DI::getDefault();
        } elseif ($make instanceof DI) {
            return DI::setDefault($make);
        }

        return DI::getDefault()->get($make, $parameters);
    }
}

if ( ! function_exists('config'))
{
    /**
     * Get / set the specified configuration value.
     *
     * If an array is passed as the key, we will assume you want to set an array of values.
     *
     * @param  array|string  $key
     * @param  mixed  $default
     * @return mixed
     */
    function config($key = null, $default = null)
    {
        $config = di('config');
        if (is_null($key)) {
            return $config;
        }

        if ($config instanceof Config) {
            if (preg_match('/(\w+)\./i', $key, $matches)) {
                $scope = $matches[1];
                if (!$config->has($scope)) {
                    $data = di('resolver')->run('config:load', function($scope) {
                        if (defined('PATH_APP_CONFIG')) {
                            return PATH_APP_CONFIG . "/$scope.php";
                        }
                    }, [$scope]);
                    $config->sets($data);
                }
                return $config->get($key, $default);
            }
        }

        return $default;
    }
}

if ( ! function_exists('session'))
{
    /**
     * Get session value by key
     *
     * @param  array|string  $key
     * @param  mixed  $default
     * @return mixed
     */
    function session($key = null, $default = null)
    {
        $session = di('session');
        if (is_null($key)) {
            return $session;
        }

        return $session->get($key, $default);
    }
}

if ( ! function_exists('db'))
{
    /**
     * Get default db connection
     *
     * @return mixed
     */
    function db()
    {
        return di('db');
    }
}

if ( ! function_exists('route'))
{
    /**
     * Get URI by route's name
     *
     * @param string    $name
     * @param array     $params
     * @param bool|true $static generate static uri
     * @return mixed
     */
    function route($name, $params = [], $static = true)
    {
        $params['for'] = $name;
        $url = di('url');
        return $static ? $url->getStatic($params) : $url->get($params);
    }
}

if ( ! function_exists('server'))
{
    /**
     * Get $_SERVER value
     * 
     * @param string $name
     * @param mixed $default
     * @return string
     */
    function server($name, $default = true)
    {
        return isset($_SERVER[$name]) ? $_SERVER[$name] : $default;
    }
}

if ( ! function_exists('forward'))
{
    /**
     * Forward to another module-controller-action
     *
     * @param string $action
     * @param string $controller
     * @param string $module
     * @param string $params
     * @return mixed
     */
    function forward($action, $controller = null, $module = null, $params = null)
    {
        $dispatcher = di('dispatcher');
        $resolver   = di('resolver');

        $forward    = ['action' => $action];
        if (is_null($module)) {
            $router = di('router');
            $module = $router->getModuleName();
        }
        if (!empty($controller)) {
            $forward['controller'] = $resolver->run('dispatch:forward', function() use ($controller, $module) {
                return "\\App\\Modules\\$module\\Controllers\\$controller";
            }, [$controller, $module]);
        }
        if (is_array($params)) {
            $forward['params'] = $params;
        }

        return $dispatcher->forward($forward);
    }
}

if ( ! function_exists('resolver'))
{
    /**
     * Get Resolver
     *
     * @return Resolver
     */
    function resolver()
    {
        return di('resolver');
    }
}

if ( ! function_exists('view'))
{
    /**
     * Get view
     *
     * @param string     $path
     * @param array      $data
     * @param bool|false $render
     * @return \Engine\View\Factory|string
     */
    function view($path = null, $data = [], $render = false)
    {
        if (is_null($path)) {
            return di('view');
        }

        $view = di(ViewContract::class);
        $view = resolver()->run('view:render', function($view, $path) {
            return $view->setPath($path);
        }, [$view, $path]);

        if (is_array($data) && count($data)) {
            foreach ($data as $key => $value) {
                $view->setVar($key, $value);
            }
        }

        if ($render) {
            return $view->render($view->getPath(), $data);
        }

        return $view;
    }
}

if ( ! function_exists('partial'))
{
    /**
     * Partial View
     *
     * @param string $path
     * @param array  $data
     * @param bool   $buffer
     * @return string
     */
    function partial($path, $data = [], $buffer = false)
    {
        $view = di('view');
        $path = resolver()->run('view:partial', function($path) {
            return $path;
        }, [$path]);

        if ($buffer) {
            ob_start();
        }

        $view->partial($path, $data);

        if ($buffer) {
            return ob_get_clean();
        }
    }
}

if ( ! function_exists('with'))
{
    /**
     * Return input object
     *
     * @param mixed $object
     * @return mixed
     */
    function with($object)
    {
        return $object;
    }
}