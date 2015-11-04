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
    public function testSetGetArrayConfig(Config $config)
    {
        $config = clone $config;

        $this->assertNull($config->sets(['a' => 'b'], true));

        $array  = ['a' => 'b', 'c' => ['d' => 'e']];
        $config->sets($array);
        $this->assertEquals($array, $config->gets());

        $merged = array_merge($array, ['a' => 'd']);
        $config->sets(['a' => 'd'], true);
        $this->assertEquals($merged, $config->gets());
    }

    /**
     * @depends testImplementContract
     */
    public function testSetGetConfig(Config $config)
    {
        $config = clone $config;

        $array  = ['a' => 'b', 'c' => ['d' => 'e', 'g' => ['h' => 'u']]];
        $config->sets($array);

        $this->assertEquals('b', $config->get('a'));
        $this->assertEquals('e', $config->get('c.d'));
        $this->assertEquals(['h' => 'u'], $config->get('c.g'));
        $this->assertEquals('u', $config->get('c.g.h'));
    }
}