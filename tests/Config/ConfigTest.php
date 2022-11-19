<?php 

use PHPUnit\Framework\TestCase;

use SigmaPHP\Core\Config\Config;

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
        $this->assertEquals($this->config->get('hello'), 'world');
    }

    /**
     * Test get config method return null when the key 
     * is not found.
     *
     * @return void
     */
    public function testGetConfigReturnsNullWhenKeyIsNotFound()
    {
        $this->config->set('hello', 'world');
        $this->assertNull($this->config->get('bye'));
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