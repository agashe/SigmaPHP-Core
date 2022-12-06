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

            case 'test':
                $this->runTests();
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
            $envFile = file_get_Contents(dirname(__DIR__, 5) . '/.env');
            
            if (strpos($envFile, 'APP_KEY') !== FALSE) {
                $oldKey = substr($envFile, strpos($envFile, 'APP_KEY') + 8, 32);
                $envFile = str_replace($oldKey, $key, $envFile);
            } else {
                $envFile .= 'APP_SECRET_KEY="' . $key .'"';
            }

            file_put_contents(dirname(__DIR__, 5) . '/.env', $envFile);
        } catch (\Exception $e) {
            echo $e;
        }
    }


    /**
     * Return the CLI command for the database handler.
     * 
     * @param string $command
     * @return string
     */
    private function dbConsoleCommand($command)
    {
        return 
            "./vendor/bin/phinx {$command} --configuration config/database.php";
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
            $this->dbConsoleCommand("create {$fileName}")
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
            $this->dbConsoleCommand("seed:create {$seederName}")
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
            $this->dbConsoleCommand("migrate")
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
            $this->dbConsoleCommand("rollback")
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
            $this->dbConsoleCommand("seed:run")
        );
    }

    /**
     * Create new controller.
     * 
     * @param string $controllerName
     * @return void
     */
    private function createController($controllerName)
    {
        $path = dirname(__DIR__, 5) . '/src/Controllers/';

        $content = <<< MODEL_CONTENT
        <?php
        
        namespace SigmaPHP\Controllers;

        use SigmaPHP\Core\Controllers\BaseController;

        class $controllerName extends BaseController
        {
            /**
             * $controllerName Constructor
             */
            public function __construct()
            {
                parent::__construct();
            }
        }
        MODEL_CONTENT;

        file_put_contents($path . $controllerName . '.php', $content);
    }

    /**
     * Create new data model.
     * 
     * @param string $modelName
     * @return void
     */
    private function createModel($modelName)
    {
        $path = dirname(__DIR__, 5) . '/src/Models/';

        $content = <<< MODEL_CONTENT
        <?php
        
        namespace SigmaPHP\Models;

        use SigmaPHP\Core\Models\BaseModel;

        class $modelName extends BaseModel
        {
            
        }
        MODEL_CONTENT;

        file_put_contents($path . $modelName . '.php', $content);
    }

    /**
     * Create new html view.
     * 
     * @param string $viewName
     * @return void
     */
    private function createView($viewName)
    {
        $path = dirname(__DIR__, 5) . '/src/Views/';
        file_put_contents($path . $viewName . '.blade.php', '');
    }

    /**
     * Clear views cache.
     * 
     * @return void
     */
    private function clearCache()
    {
        $path = dirname(__DIR__, 5) . '/storage/cache';
        
        if ($handle = opendir($path)) {
            while (($file = readdir($handle))) {
                if (in_array($file, ['.', '..'])) continue;
                unlink($file);
            }
        
            closedir($handle);
        }
    }

    /**
     * Run test units.
     * 
     * @return void
     */
    private function runTests()
    {
        exec("./vendor/bin/phpunit tests");
    }
}
