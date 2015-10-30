<?php namespace Engine\Tests\Resolver;

use Engine\Tests\TestCase;
use Engine\Resolver\Factory as Resolver;
use Engine\Resolver\Contract as ResolverContract;

class ResolverTest extends TestCase
{
    public function testImplementContract()
    {
        $resolver = new Resolver();
        $this->assertInstanceOf(ResolverContract::class, $resolver);
        return $resolver;
    }

    /**
     * @depends testImplementContract
     */
    public function testSetGetRunResolver(Resolver $resolver)
    {
        $arg  = 4;
        $test = 5;
        $resolver->set('my:resolver', function($arg = null) use ($test) {
            return $test . ($arg ? $arg : '');
        });
        $func = $resolver->get('my:resolver');
        $this->assertTrue(is_callable($func));
        $this->assertEquals($test, $resolver->run('my:resolver'));
        $this->assertEquals($test . $arg, $resolver->run('my:resolver', null, [$arg]));
    }
}