<?php namespace Engine\Config;

use Engine\Exception\InvalidParameterException;

class Factory implements Contract
{
    /**
     * @var array
     */
    protected $data = [];

    /**
     * Path to folder which stores config
     *
     * @var string
     */
    protected $path;

    public function __construct($path = null)
    {
        $this->path = $path;
    }

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
        $config = require "{$this->path}/$scope.php";
        $this->data[$scope] = $config;
    }
}