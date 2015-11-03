<?php namespace Engine\Tests\View;

use Engine\Tests\TestCase;
use Engine\View\ServiceRegister as ViewRegister;
use Engine\View\Contract as ViewContract;

class ViewTest extends TestCase
{
    public function testImplementContract()
    {
        $register = new ViewRegister();
        $register->setDI(di());
        $register->onBoot();

        $view = view();
        $this->assertInstanceOf(ViewContract::class, $view);
        return $view;
    }

    public function testViewRender()
    {
        $resolver = di('resolver');
        $resolver->set('view:render', function($view, $path) {
            return $view->setPath("views/$path");
        });

        // Test path : No Render
        $view = view('my_path', []);
        $this->assertEquals('views/my_path', $view->getPath());

        // Test render
        $resolver->set('view:render', function($view, $path) {
            return $view->setPath(__DIR__ . "/views/$path.phtml");
        });
        $content = view('view', [], true);
        $this->assertEquals('Hello World!', $content);

        // Test render with param
        $content = view('view_params', ['name' => 'John Doe'], true);
        $this->assertEquals('Hello John Doe', $content);
    }

    /**
     * @depends testImplementContract
     */
    public function testPartialRender()
    {
        $resolver = di('resolver');
        $resolver->set('view:partial', function($path) {
            return __DIR__ . "/views/$path.phtml";
        });

        $content = partial('view_params', ['name' => 'John Doe'], true);
        $this->assertEquals('Hello John Doe', $content);
    }
}