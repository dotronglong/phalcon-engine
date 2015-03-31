<?php namespace Engine;

class Engine
{
    /**
     * Global Engine Instance
     *
     * @var \Engine\Engine
     */
    protected static $instance;

    /**
     * Global Configuration
     *
     * @var \Phalcon\Config
     */
    protected static $config;

    /**
     * Get Engine instance
     *
     * @return \Engine\Engine
     */
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * Get Static Configuration
     *
     * Priority Loaded:
     * 1. config/domains/[domain].php
     * 2. config/app.php
     *
     * @reutrn \Phalcon\Config
     */
    public static function config()
    {
        if (self::$config === null) {
            $domain       = $_SERVER['SERVER_NAME'];
            $domainConfig = PATH_APP_CONFIG . DS . 'domains' . DS . $domain . '.php';
            if (file_exists($domainConfig)) {
                $config   = include_once $domainConfig;
            } else {
                $config   = include_once PATH_APP_CONFIG . DS . 'app.php';
            }
            self::$config = new \Phalcon\Config($config);
        }

        return self::$config;
    }
}