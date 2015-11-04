<?php namespace Engine\Tests\Config;

use Engine\Tests\TestCase;
use Engine\Config\Factory as Config;
use Engine\Config\Contract as ConfigContract;

class ConfigTest extends TestCase
{
    public function testImplementContract()
    {
        $config = new Config();
        $this->assertInstanceOf(ConfigContract::class, $config);
        return $config;
    }

    /**
     * @depends testImplementContract
     */
    public function testSetArrayConfig(Config $config)
    {
        $config = clone $config;
        $config->
    }
}