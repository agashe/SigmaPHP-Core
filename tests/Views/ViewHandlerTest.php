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
     * ConfigTest SetUp
     *
     * @return void
     */
    public function setUp(): void
    {
        if (!is_dir('templates')) {
            mkdir('templates');
        }
        
        if (!is_dir('cache')) {
            mkdir('cache');
        }
        
        $this->viewHandler = new ViewHandler('templates', 'cache');
    }

    /**
     * Test render method.
     *
     * @return void
     */
    public function testRender()
    {
        file_put_contents(
            'templates/index.template.html', 
            '<h1>hello {{ $name }}</h1>'
        );

        $this->viewHandler->render('index', [
            'name' => 'world'
        ]);

        $this->expectOutputString('<h1>hello world</h1>');
    }

    /**
     * ConfigTest tearDown
     *
     * @return void
     */
    public function tearDown(): void
    {
        if (file_exists('templates/index.template.html')) {
            unlink('templates/index.template.html');
        }

        if (is_dir('templates')) {
            rmdir('templates');
        }

        $cacheFiles = glob('cache/*');
        
        foreach ($cacheFiles as $cacheFile) {
            if (file_exists($cacheFile)) {
                unlink($cacheFile);
            }
        }
        
        if (is_dir('cache')) {
            rmdir('cache');
        }
    }
}