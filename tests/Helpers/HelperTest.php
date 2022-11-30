<?php 

use PHPUnit\Framework\TestCase;

use SigmaPHP\Core\Helpers\Helper;

/**
 * Helper Test
 */
class HelperTest extends TestCase
{
    /**
     * @var Helper $helper
     */
    private $helper;

    /**
     * HelperTest SetUp
     *
     * @return void
     */
    public function setUp(): void
    {
        $this->helper = new Helper();
    }

    /**
     * Test get environment variable.
     *
     * @return void
     */
    public function testGetEnvVariable()
    {
        $_ENV['test'] = 'hello world';

        $this->assertEquals('hello world', $this->helper->env('test'));
    }

    /**
     * Test generate URL from path.
     *
     * @return void
     */
    public function testGenerateUrlFromPath()
    {
        $_SERVER['SERVER_NAME'] = 'localhost';

        $expected = 'http://localhost/test';

        $this->assertEquals($expected, $this->helper->url('/test'));
    }
    
    /**
     * Test encrypt text.
     *
     * @return void
     */
    public function testEncryptText()
    {
        $_ENV['APP-SECRET-KEY'] = 'super secret key';

        $expected = 'Aua30ZjVa7sRdmIGi8i+lw==';

        $this->assertEquals(
            $expected,
            $this->helper->encrypt('test', base64_encode('1234567890'))
        );
    }
    
    /**
     * Test decrypt text.
     *
     * @return void
     */
    public function testDecryptText()
    {
        $_ENV['APP-SECRET-KEY'] = 'super secret key';

        $expected = 'test';

        $this->assertEquals(
            $expected,
            $this->helper->decrypt(
                'Aua30ZjVa7sRdmIGi8i+lw==',
                base64_encode('1234567890')
            )
        );
    }
}