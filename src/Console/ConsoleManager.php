<?php

namespace SigmaPHP\Core\Console;

use PassGen\PassGen;

/**
 * Console Manager Class
 */
class ConsoleManager
{
    /**
     * Execute console commands.
     * 
     * @param string $input
     * @return void
     */
    final public function execute($input)
    {
        $command = $input[1];
        $argument = $input[2] ?? null;

        switch ($command) {
            case 'version':
                $this->version();
                break;

            case 'help':
                # code...
                break;

            case 'run':
                $this->runServer($argument);
                break;

            case 'generate:app-secret-key':
                $this->generateAppSecretKey();
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
                $this->commandNotFound();
                break;
        }
    }

    /**
     * Default error message.
     * 
     * @return void
     */
    private function commandNotFound()
    {
        $message = "Invalid command.\n\r";
        $message .= "Type 'php sigma-cli help' command for help.\n\r";
        print($message);
    }

    /**
     * Print framework version.
     * 
     * @return void
     */
    private function version()
    {
        print("SigmaPHP framework version 0.1.0\n\r");
    }

    /**
     * Run PHP built in server.
     * 
     * @param int $port
     * @return void
     */
    private function runServer($port = 8888)
    {
        exec("cd public; php -S localhost:$port");
    }

    /**
     * Generate secure key.
     * 
     * @return void
     */
    private function generateAppSecretKey()
    {
        $passGen = new PassGen(32);

        $key = $passGen->create();

        // replace invalid symbols from the generated key
        $key  = str_replace('#', 'A', $key);
        $key  = str_replace('\'', 'B', $key);
        $key  = str_replace('"', 'C', $key);

        try {
            $envFile = file_get_Contents(dirname(__DIR__, 2) . '/.env');
            
            if (strpos($envFile, 'APP_KEY') !== FALSE) {
                $oldKey = substr($envFile, strpos($envFile, 'APP_KEY') + 8, 32);
                $envFile = str_replace($oldKey, $key, $envFile);
            } else {
                $envFile .= 'APP_KEY=' . $key;
            }

            file_put_contents(dirname(__DIR__, 2) . '/.env', $envFile);
        } catch (\Exception $e) {
            echo $e;
        }
    }
}
