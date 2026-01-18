<?php

use PHPUnit\Framework\TestCase;

use SigmaPHP\Core\Views\ViewHandler;

/**
 * ViewHandler Test
 */
class ViewHandlerTest extends TestCase
{
    /**
     * @var ViewHandler $viewHandler
     */
    private $viewHandler;

    /**
     * ViewHandlerTest SetUp
     *
     * @return void
     */
    public function setUp(): void
    {
        $this->viewHandler = new ViewHandler('templates', 'cache');
    }

    /**
     * Test render method.
     *
     * @return void
     */
    public function testRender()
    {
        if (file_exists('templates/index.template.html')) {
            file_put_contents(
                'templates/index.template.html',
                '<h1>hello {{ $name }}</h1>'
            );
        }

        print $this->viewHandler->render('index', [
            'name' => 'world'
        ]);

        $this->expectOutputString('<h1>hello world</h1>');
    }
}
