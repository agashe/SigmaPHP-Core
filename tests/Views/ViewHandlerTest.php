<?php 

use PHPUnit\Framework\TestCase;

use SigmaPHP\Core\Views\ViewHandler;

/**
 * ViewHandler Test
 */
class ConfigTest extends TestCase
{
    /**
     * @var ViewHandler $viewHandler
     */
    private $viewHandler;

    /**
     * ConfigTest SetUp
     *
     * @return void
     */
    public function setUp(): void
    {
        $this->viewHandler = new ViewHandler(
            dirname(__DIR__, 2) . '/templates',
            dirname(__DIR__, 2) . '/cache'
        );
    }

    /**
     * Test render method.
     *
     * @return void
     */
    public function testRender()
    {
        $this->viewHandler->render('hello', 'world');
        $this->expectOutputString('');
    }
}