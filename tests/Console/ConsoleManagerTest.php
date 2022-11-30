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
        $this->consoleManager = new ConsoleManager();
    }

    /**
     * Test execute command method.
     *
     * @return void
     */
    public function testExecute()
    {
        $this->consoleManager->execute('version');
        $this->expectOutputString('SigmaPHP framework version 0.1.0');
    }
    
    /**
     * Test sorry message will be printed for unknown commands.
     *
     * @return void
     */
    public function testUnknownCommand()
    {
        $this->consoleManager->execute('my-command');

        $expectedMessage = "Invalid command.\n\r";
        $expectedMessage .= "Type 'php sigma-cli help' command for help.\n\r";

        $this->expectOutputString($expectedMessage);
    }
}