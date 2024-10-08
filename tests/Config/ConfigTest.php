<?php 

use PHPUnit\Framework\TestCase;

use SigmaPHP\Core\Config\Config;

/**
 * Config Test
 */
class ConfigTest extends TestCase
{
    /**
     * @var Config $config
     */
    private $config;

    /**
     * ConfigTest SetUp
     *
     * @return void
     */
    public function setUp(): void
    {
        $this->config = new Config();
    }

    /**
     * Test set config method.
     *
     * @return void
     */
    public function testSetConfig()
    {
        $this->assertTrue($this->config->set('hello', 'world'));
    }
    
    /**
     * Test has config method.
     *
     * @return void
     */
    public function testHasConfig()
    {
        $this->config->set('hello', 'world');
        $this->assertTrue($this->config->has('hello'));
    }
    
    /**
     * Test get config method.
     *
     * @return void
     */
    public function testGetConfig()
    {
        $this->config->set('hello', 'world');
        $this->assertEquals('world', $this->config->get('hello'));
    }
    
    /**
     * Test get config using dot notation.
     *
     * @return void
     */
    public function testGetConfigUsingDotNotation()
    {
        $configs = [
            'api' => [
                'ver' => '1.0.0'
            ]
        ];

        $this->config->set('app', $configs);
        $this->assertEquals('1.0.0', $this->config->get('app.api.ver'));
    }
    
    /**
     * Test get config will return default value if set.
     *
     * @return void
     */
    public function testGetConfigWillReturnDefaultValueIfSet()
    {
        $this->assertEquals('world', $this->config->get('hello', 'world'));
    }

    /**
     * Test get config method return empty string when the key 
     * is not found.
     *
     * @return void
     */
    public function testGetConfigReturnsEmptyStringWhenKeyIsNotFound()
    {
        $this->config->set('hello', 'world');
        $this->assertEmpty($this->config->get('bye'));
    }

    /**
     * Test get config method return empty string when the key 
     * is not found using dot notation.
     *
     * @return void
     */
    public function testConfigReturnsEmptyStringIfKeyNotFoundUsingDotNotation()
    {
        $this->config->set('test', ['something' => []]);
        $this->assertEmpty($this->config->get('test.something.not_found'));
    }

    /**
     * Test get all config method.
     *
     * @return void
     */
    public function testGetAllConfig()
    {
        $this->config->set('hello', 'world');
        $this->config->set('bye', 'world');
        $this->assertIsArray($this->config->getAll());
    }
}