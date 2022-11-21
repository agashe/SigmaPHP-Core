<?php 

use PHPUnit\Framework\TestCase;

use SigmaPHP\Core\Http\Cookie;

/**
 * Cookie Test
 */
class CookieTest extends TestCase
{
    /**
     * @var Cookie $cookie
     */
    private $cookie;

    /**
     * CookieTest SetUp
     *
     * @return void
     */
    public function setUp(): void
    {
        $this->cookie = new Cookie();
    }
   
    /**
     * Test cookie is set.
     *
     * @runInSeparateProcess
     * @return void
     */
    public function testCookieIsSet()
    {
        $this->assertTrue($this->cookie->set('hello', 'world'));
    }

    /**
     * Test throws exception if no name was provided to set cookie.
     *
     * @runInSeparateProcess
     * @return void
     */
    public function testThrowsExceptionIfNoNameWasProvidedToSetCookie()
    {
        $this->expectException(\Exception::class);
        $this->cookie->set(null, 'world');
    }

    /**
     * Test throws exception if no value was provided to set cookie.
     *
     * @runInSeparateProcess
     * @return void
     */
    public function testThrowsExceptionIfNoValueWasProvidedToSetCookie()
    {
        $this->expectException(\Exception::class);
        $this->cookie->set(null, 'world');
    }

    /**
     * Test get cookie.
     *
     * @runInSeparateProcess
     * @return void
     */
    public function testGetCookie()
    {
        $_COOKIE['hello'] = 'world';

        $this->assertEquals('world', $this->cookie->get('hello'));
    }

    /**
     * Test get cookie returns false if the name is null.
     *
     * @runInSeparateProcess
     * @return void
     */
    public function testGetCookieReturnsFalseIfTheNameIsNull()
    {
        $this->assertFalse($this->cookie->get());
    }
    
    /**
     * Test get cookie returns false if the cookie does not exist.
     *
     * @runInSeparateProcess
     * @return void
     */
    public function testGetCookieReturnsFalseIfTheCookieDoesNotExist()
    {
        $this->assertFalse($this->cookie->get('foo'));
    }

    /**
     * Test delete cookie.
     *
     * @runInSeparateProcess
     * @return void
     */
    public function testDeleteCookie()
    {
        $_COOKIE['hello'] = 'world';

        $this->assertTrue($this->cookie->delete('hello'));
    }

    /**
     * Test delete cookie returns false if the name is null.
     *
     * @runInSeparateProcess
     * @return void
     */
    public function testDeleteCookieReturnsFalseIfTheNameIsNull()
    {
        $this->assertFalse($this->cookie->delete());
    }
    
    /**
     * Test delete cookie returns false if the cookie does not exist.
     *
     * @runInSeparateProcess
     * @return void
     */
    public function testDeleteCookieReturnsFalseIfTheCookieDoesNotExist()
    {
        $this->assertFalse($this->cookie->delete('foo'));
    }
}