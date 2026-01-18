<?php

use PHPUnit\Framework\TestCase;

use SigmaPHP\Core\Views\ViewHandler;

/**
 * CustomDirectives Test
 */
class CustomDirectivesTest extends TestCase
{
    /**
     * @var ViewHandler $viewHandler
     */
    private $viewHandler;

    /**
     * CustomDirectivesTest SetUp
     *
     * @return void
     */
    public function setUp(): void
    {
        $_SERVER['HTTPS'] = 'on';
        $_SERVER['HTTP_HOST'] = 'example.com';
        $_SERVER['REQUEST_URI'] = '';

        $this->viewHandler = new ViewHandler('templates', '');
    }

    /**
     * Test "current" directive.
     *
     * @runInSeparateProcess
     * @return void
     */
    public function testCurrentDirective()
    {
        // fake url
        $_SERVER['REQUEST_URI'] = 'api/v1/user?name=ahmed';

        // update the template
        if (file_exists('templates/index.template.html')) {
            file_put_contents(
                'templates/index.template.html',
                '<div>{% current() %}</div>'
            );
        }

        print $this->viewHandler->render('index');

        $this->expectOutputString(
            '<div> https://example.com/api/v1/user?name=ahmed </div>'
        );
    }

    /**
     * Test "previous" directive.
     *
     * @runInSeparateProcess
     * @return void
     */
    public function testPreviousDirective()
    {
        $_SERVER['HTTP_REFERER'] = 'https://example.com/products';

        // update the template
        if (file_exists('templates/index.template.html')) {
            file_put_contents(
                'templates/index.template.html',
                '<div>{% previous() %}</div>'
            );
        }

        print $this->viewHandler->render('index');

        $this->expectOutputString('<div> https://example.com/products </div>');
    }

    /**
     * Test "cookie" directive.
     *
     * @runInSeparateProcess
     * @return void
     */
    public function testCookieDirective()
    {
        $_COOKIE['hello'] = 'world';

        // update the template
        if (file_exists('templates/index.template.html')) {
            file_put_contents(
                'templates/index.template.html',
                '<p>{% cookie("hello") %}</p>'
            );
        }

        print $this->viewHandler->render('index');

        $this->expectOutputString('<p> world </p>');
    }

    /**
     * Test "session" directive.
     *
     * @runInSeparateProcess
     * @return void
     */
    public function testSessionDirective()
    {
        @session_start();
        $_SESSION['hello'] = 'world';

        // update the template
        if (file_exists('templates/index.template.html')) {
            file_put_contents(
                'templates/index.template.html',
                '<p>{% session("hello") %}</p>'
            );
        }

        print $this->viewHandler->render('index');

        $this->expectOutputString('<p> world </p>');

        unset($_SESSION['hello']);
    }

    /**
     * Test "method" directive.
     *
     * @runInSeparateProcess
     * @return void
     */
    public function testMethodDirective()
    {
        // update the template
        if (file_exists('templates/index.template.html')) {
            file_put_contents(
                'templates/index.template.html',
                '{% method("put") %}'
            );
        }

        print $this->viewHandler->render('index');

        $this->expectOutputString(
            ' <input type="hidden" name="_method" value="put" /> '
        );
    }

    /**
     * Test "asset" directive.
     *
     * @runInSeparateProcess
     * @return void
     */
    public function testAssetDirective()
    {
        // update the template
        if (file_exists('templates/index.template.html')) {
            file_put_contents(
                'templates/index.template.html',
                '<script src="{% asset("js/script.js") %}"></script>'
            );
        }

        print $this->viewHandler->render('index');

        $this->expectOutputString(
            '<script src=" https://example.com/static/js/script.js "></script>'
        );
    }
}
