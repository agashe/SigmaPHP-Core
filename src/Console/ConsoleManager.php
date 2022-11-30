<?php

namespace SigmaPHP\Core\Console;

/**
 * Console Manager Class
 */
class ConsoleManager
{
    /**
     * Execute console commands.
     * 
     * @param string $command
     * @return void
     */
    final public function execute($command)
    {
        switch ($command) {
            case 'version':
                print("SigmaPHP framework version 0.1.0\n");
                break;

            case 'help':
                # code...
                break;

            case 'run':
                exec('cd public; php -S localhost:8888');
                break;

            case 'generate:app-secret-key':
                // ToDo
                break;

            case 'create:migration':
                // ToDo
                break;

            case 'create:seeder':
                // ToDo
                break;

            case 'create:controller':
                // ToDo
                break;

            case 'create:model':
                // ToDo
                break;

            case 'create:view':
                // ToDo
                break;

            case 'migrate':
                // ToDo
                break;

            case 'rollback':
                // ToDo
                break;

            case 'seed':
                // ToDo
                break;

            case 'clear':
                // ToDo
                break;

            default:
                $message = "Invalid command.\n\r";
                $message .= "Type 'php sigma-cli help' command for help.\n\r";
                print($message);
                break;
        }
    }
}
