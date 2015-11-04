<?php namespace Engine\Tests;

use Engine\DI\Contract as DI;
use Engine\Config\Factory as Config;
use Phalcon\Session\AdapterInterface as SessionInterface;

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
    
    public function testFunctionDiExists()
    {
        $this->assertTrue(function_exists('di'));
    }
    
    /**
     * @depends testFunctionDiExists
     */
    public function testReturnDiInterface()
    {
        $di = di();
        $this->assertInstanceOf(DI::class, $di);
        return $di;
    }
    
    public function testFunctionConfigExists()
    {
        $this->assertTrue(function_exists('config'));
    }
    
    public function testFunctionSessionExists()
    {
        $this->assertTrue(function_exists('session'));
    }
    
    /**
     * @depends testReturnDiInterface
     * @depends testFunctionSessionExists
     */
    public function testSetSession(DI $di)
    {
        $di->set('session', new \Phalcon\Session\Adapter\Files);
        $session = session();
        $this->assertInstanceOf(SessionInterface::class, $session);
        $session->set('var', 'value');
        $this->assertTrue($session->has('var'));
    }
    
    /**
     * @depends testSetSession
     */
    public function testGetSession()
    {
        $this->assertEquals(session('var'), 'value');
    }

    /**
     * @depends testSetSession
     */
    public function testGetSessionWithDefaultNullValue()
    {
        $this->assertNull(session('var_name'));
    }

    public function testGetBasePath()
    {
        $this->assertEquals('my_path', base_path('my_path'));
        define('PATH_ROOT', 'root');
        $this->assertEquals('root/my_path', base_path('/my_path'));
    }

    public function testGetAppPath()
    {
        $this->assertEquals('my_path', app_path('my_path'));
        define('PATH_APP', 'root/app');
        $this->assertEquals('root/app/my_path', app_path('/my_path'));
    }
}