<?php namespace Engine\Tests;

class GeneralTest extends TestCase
{
    public function testFunctionEnvExists()
    {
        $this->assertTrue(function_exists('env'));
    }
    
    /**
     * @depends testFunctionEnvExists
     */
    public function testGetEnv()
    {
        putenv("var=value");
        $this->assertEquals(env('var'), 'value');
    }
    
    /**
     * @depends testGetEnv
     */
    public function testGetEnvWithDefaultValue()
    {
        $var_name    = 'this_is_var';
        $var_default = 'DEFAULT';
        $this->assertEquals(env($var_name, $var_default), $var_default);
    }
}