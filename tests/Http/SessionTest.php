<?php 

use PHPUnit\Framework\TestCase;

use SigmaPHP\Core\Http\Session;

/**
 * Session Test
 */
class SessionTest extends TestCase
{
    /**
     * @var Session $session
     */
    private $session;

    /**
     * SessionTest SetUp
     *
     * @return void
     */
    public function setUp(): void
    {
        $this->session = new Session();
    }
   
    /**
     * Test session is set.
     *
     * @runInSeparateProcess
     * @return void
     */
    public function testSessionIsSet()
    {
        $this->assertTrue($this->session->set('hello', 'world'));
    }

    /**
     * Test throws exception if no name was provided to set session.
     *
     * @runInSeparateProcess
     * @return void
     */
    public function testThrowsExceptionIfNoNameWasProvidedToSetSession()
    {
        $this->expectException(\Exception::class);
        $this->session->set(null, 'world');
    }

    /**
     * Test throws exception if no value was provided to set session.
     *
     * @runInSeparateProcess
     * @return void
     */
    public function testThrowsExceptionIfNoValueWasProvidedToSetSession()
    {
        $this->expectException(\Exception::class);
        $this->session->set(null, 'world');
    }

    /**
     * Test get session.
     *
     * @runInSeparateProcess
     * @return void
     */
    public function testGetSession()
    {
        @session_start();
        $_SESSION['hello'] = 'world';

        $this->assertEquals('world', $this->session->get('hello'));
    }

    /**
     * Test get session returns false if the name is null.
     *
     * @runInSeparateProcess
     * @return void
     */
    public function testGetSessionReturnsFalseIfTheNameIsNull()
    {
        $this->assertFalse($this->session->get());
    }
    
    /**
     * Test get session returns false if the session does not exist.
     *
     * @runInSeparateProcess
     * @return void
     */
    public function testGetSessionReturnsFalseIfTheSessionDoesNotExist()
    {
        $this->assertFalse($this->session->get('foo'));
    }

    /**
     * Test delete session.
     *
     * @runInSeparateProcess
     * @return void
     */
    public function testDeleteSession()
    {
        $_SESSION['hello'] = 'world';

        $this->assertTrue($this->session->delete('hello'));
    }

    /**
     * Test delete session returns false if the name is null.
     *
     * @runInSeparateProcess
     * @return void
     */
    public function testDeleteSessionReturnsFalseIfTheNameIsNull()
    {
        $this->assertFalse($this->session->delete());
    }
    
    /**
     * Test delete session returns false if the session does not exist.
     *
     * @runInSeparateProcess
     * @return void
     */
    public function testDeleteSessionReturnsFalseIfTheSessionDoesNotExist()
    {
        $this->assertFalse($this->session->delete('foo'));
    }
}