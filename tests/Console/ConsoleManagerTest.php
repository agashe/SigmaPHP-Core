<?php 

use PHPUnit\Framework\TestCase;
use SigmaPHP\Core\Console\ConsoleManager;

/**
 * Console Manager Test
 */
class ConsoleManagerTest extends TestCase
{
    /**
     * @var ConsoleManager $consoleManager
     */
    private $consoleManager;

    /**
     * ConsoleManagerTest SetUp
     *
     * @return void
     */
    public function setUp(): void
    {
        if (!file_exists('.env')) {
            file_put_contents('.env', '');
        }

        $this->consoleManager = new ConsoleManager();
    }

    /**
     * ConsoleManagerTest TearDown
     *
     * @return void
     */
    public function tearDown(): void
    {
        if (file_exists('.env')) {
            unlink('.env');
        }
    }

    /**
     * Test execute command method.
     *
     * @return void
     */
    public function testExecute()
    {
        $input = [
            'php',
            'version'
        ];

        $this->consoleManager->execute($input);
        $this->expectOutputString("SigmaPHP framework version " . 
            SigmaPHP\Core\App\Kernel::SIGMAPHP_FRAMEWORK_VERSION
        . "\n");
    }
    
    /**
     * Test sorry message will be printed for unknown commands.
     *
     * @return void
     */
    public function testUnknownCommand()
    {
        $input = [
            'php',
            'my-command'
        ];

        $this->consoleManager->execute($input);

        $expectedMessage = "Invalid command.\n";
        $expectedMessage .= "Type 'php sigma-cli help' command for help.\n";

        $this->expectOutputString($expectedMessage);
    }
}