<?php 

use PHPUnit\Framework\TestCase;

use SigmaPHP\Core\App\Kernel;

/**
 * Helper Test
 */
class HelperTest extends TestCase
{
    /**
     * @var Kernel $kernel
     */
    private $kernel;

    /**
     * HelperTest SetUp
     *
     * @return void
     */
    public function setUp(): void
    {
        // initialize new app
        $this->kernel = new Kernel();

        // create dummy config file
        if (!is_dir('config')) {
            mkdir('config');
        }

        if (!file_exists('config/app.php')) {
            file_put_contents(
                'config/app.php', 
                '<?php return ["api" => ["version" => "1.0.0"],'.
                '"routes_path" => "routes/"];'
            );
        }

        // create dummy .env file
        if (!file_exists('.env')) {
            file_put_contents('.env', 'APP_ENV="development"');
        }
        

        // create dummy routes file
        if (!is_dir('routes')) {
            mkdir('routes');
        }
        
        if (!file_exists('web.php')) {
            file_put_contents(
                'routes/web.php', 
                '<?php return [["path" => "/test", "name" => "test"]];'
            );
        }
    }

    /**
     * HelperTest TearDown
     *
     * @return void
     */
    public function tearDown(): void
    {
        // remove the dummy config file
        if (file_exists('config/app.php')) {
            unlink('config/app.php');
        }

        if (is_dir('config')) {
            rmdir('config');
        }

        // remove the dummy .env file
        if (file_exists('.env')) {
            unlink('.env');
        }
        
        // remove the dummy routes file
        if (file_exists('routes/web.php')) {
            unlink('routes/web.php');
        }

        if (is_dir('routes')) {
            rmdir('routes');
        }
    }
    
    /**
     * Test get container instance.
     *
     * @return void
     */
    public function testGetContainerInstance()
    {
        $this->assertInstanceOf(
            SigmaPHP\Container\Container::class,
            container()
        );
    }
    
    /**
     * Test get class instance from the container.
     *
     * @return void
     */
    public function testGetClassInstanceFromTheContainer()
    {
        $this->assertInstanceOf(
            SigmaPHP\Core\Config\Config::class,
            container('config')
        );
    }

    /**
     * Test get environment variable.
     *
     * @return void
     */
    public function testGetEnvVariable()
    {
        $_ENV['test'] = 'hello world';

        $this->assertEquals('hello world', env('test'));
    }

    /**
     * Test env method will return the default value if the
     * if the key does'nt exists.
     *
     * @return void
     */
    public function testEnvMethodWillReturnTheDefaultValue()
    {
        $this->assertEquals(
            'default_value', 
            env('unknown', 'default_value')
        );
    }

    /**
     * Test get config value.
     *
     * @return void
     */
    public function testGetConfigValue()
    {
        $this->assertEquals(
            '1.0.0',
            config('app.api.version')
        );
    }

    /**
     * Test config method will return the default value if the
     * if the key does'nt exists.
     *
     * @return void
     */
    public function testConfigMethodWillReturnTheDefaultValue()
    {
        $this->assertEquals(
            'default_value', 
            config('unknown', 'default_value')
        );
    }

    /**
     * Test generate URL from path using the route name.
     *
     * @return void
     */
    public function testGenerateUrlFromPathUsingTheRouteName()
    {
        $_SERVER['HTTP_HOST'] = 'localhost';

        $expected = 'http://localhost/test';

        $this->assertEquals($expected, url('test'));
    }
    
    /**
     * Test encrypt text.
     *
     * @return void
     */
    public function testEncryptText()
    {
        $_ENV['APP_SECRET_KEY'] = 'super secret key';

        $expected = 'Aua30ZjVa7sRdmIGi8i+lw==';

        $this->assertEquals(
            $expected,
            encrypt('test', base64_encode('1234567890'))
        );
    }
    
    /**
     * Test decrypt text.
     *
     * @return void
     */
    public function testDecryptText()
    {
        $_ENV['APP_SECRET_KEY'] = 'super secret key';

        $expected = 'test';

        $this->assertEquals(
            $expected,
            decrypt(
                'Aua30ZjVa7sRdmIGi8i+lw==',
                base64_encode('1234567890')
            )
        );
    }
}