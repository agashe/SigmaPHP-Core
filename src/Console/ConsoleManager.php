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
                $this->createMigrationFile($argument);
                break;

            case 'create:seeder':
                $this->createSeeder($argument);
                break;

            case 'migrate':
                $this->migrate();
                break;

            case 'rollback':
                $this->rollback();
                break;

            case 'seed':
                $this->seed();
                break;

            case 'create:controller':
                $this->createController($argument);
                break;

            case 'create:model':
                $this->createModel($argument);
                break;

            case 'create:view':
                $this->createView($argument);
                break;

            case 'clear':
                $this->clearCache();
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


    /**
     * Return the CLI command for the database handler.
     * 
     * @return string
     */
    private function dbConsoleCommand()
    {
        return "./vendor/bin/phinx --configuration config/database.php";
    }
    
    /**
     * Create new migration file.
     * 
     * @param string $fileName
     * @return void
     */
    private function createMigrationFile($fileName)
    {
        exec(
            $this->dbConsoleCommand() . " " . "create {$fileName}"
        );
    }

    /**
     * Create new seeder.
     * 
     * @param string $seederName
     * @return void
     */
    private function createSeeder($seederName)
    {
        exec(
            $this->dbConsoleCommand() . " " . "seed:create {$seederName}"
        );
    }

    /**
     * Migrate the database.
     * 
     * @return void
     */
    private function migrate()
    {
        exec(
            $this->dbConsoleCommand() . " " . "migrate"
        );
    }

    /**
     * Rollback the database.
     * 
     * @return void
     */
    private function rollback()
    {
        exec(
            $this->dbConsoleCommand() . " " . "rollback"
        );
    }

    /**
     * Seed the database.
     * 
     * @return void
     */
    private function seed()
    {
        exec(
            $this->dbConsoleCommand() . " " . "seed:run"
        );
    }

    /**
     * Create new controller.
     * 
     * @return void
     */
    private function createController()
    {
        exec(
            $this->dbConsoleCommand() . " " . "seed:run"
        );
    }

    /**
     * Create new data model.
     * 
     * @return void
     */
    private function createModel()
    {
        exec(
            $this->dbConsoleCommand() . " " . "seed:run"
        );
    }

    /**
     * Create new html view.
     * 
     * @return void
     */
    private function createView()
    {
        exec(
            $this->dbConsoleCommand() . " " . "seed:run"
        );
    }

    /**
     * Clear views cache.
     * 
     * @return void
     */
    private function clearCache()
    {
        exec(
            $this->dbConsoleCommand() . " " . "seed:run"
        );
    }
}
