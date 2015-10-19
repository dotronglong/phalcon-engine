<?php

use Phalcon\DI;
use Engine\Debug\Dumper;
use Engine\Helper\Str;

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

        switch (strtolower($value))
        {
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

        if (Str::startsWith($value, '"') && Str::endsWith($value, '"'))
        {
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
     * @return mixed|\Engine\DI\Container
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
        if (is_null($key)) return di('config');

        if (is_array($key))
        {
            return di('config')->set($key);
        }

        return di('config')->get($key, $default);
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