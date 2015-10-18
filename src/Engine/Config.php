<?php namespace Engine;

use Engine\Exception\InvalidParameterException;

class Config
{
    /**
     * @var array
     */
    protected $data = [];

    /**
     * Get configuration
     *
     * @param string $name
     * @param null   $default
     * @return mixed
     *
     * @throws InvalidParameterException
     */
    public function get($name, $default = null)
    {
        $args = explode('.', $name);
        if (count($args) < 2) {
            throw new InvalidParameterException("Invalid configuration name: $name");
        }

        $scope = $args[0];
        if (!$this->scopeExists($scope)) {
            $this->loadScope($scope);
        }

        unset($args[0]);
        $name = join('.', $args);

        return isset($this->data[$scope][$name]) ? $this->data[$scope][$name] : $default;
    }

    /**
     * Set configuration
     *
     * @param string $name
     * @param mixed  $value
     * @return static
     */
    public function set($name, $value = null)
    {
        if (is_array($name)) {
            return $this->sets($name, $value);
        }

        $args = explode('.', $name);
        if (count($args) < 2) {
            throw new InvalidParameterException("Invalid configuration name: $name");
        }

        $scope = $args[0];
        if (!isset($this->data[$scope])) {
            $this->data[$scope] = [];
        }

        unset($args[0]);
        $this->data[$scope][join('.', $args)] = $value;

        return $this;
    }

    /**
     * Set multiple configurations
     *
     * @param array  $keys
     * @param string $scope
     * @throws InvalidParameterException
     */
    protected function sets(array $keys, $scope = null)
    {
        foreach ($keys as $name => $value)
        {
            if (is_null($scope)) {
                $this->set($name, $value);
            } else {
                $this->set("$scope.$name", $value);
            }

        }
    }

    protected function scopeExists($scope)
    {
        return isset($this->data[$scope]);
    }

    protected function loadScope($scope)
    {
        $config = require PATH_APP_CONFIG . "/$scope.php";
        $this->data[$scope] = $config;
    }
}