<?php

namespace SigmaPHP\Core\Console;

use PassGen\PassGen;
use SigmaPHP\Core\Config\Config;

/**
 * Console Manager Class
 */
class ConsoleManager
{
    /**
     * @var SigmaPHP\Core\Config\Config $config
     */
    private $config;

    /**
     * ConsoleManager Constructor
     */
    public function __construct()
    {
        $this->config = new Config();
    }

    /**
     * Execute console commands.
     * 
     * @param string $input
     * @return void
     */
    final public function execute($input)
    {
        $command = $input[1] ?? 'help';
        $argument = $input[2] ?? null;

        switch ($command) {
            case 'version':
                $this->version();
                break;

            case 'help':
                $this->help();
                break;

            case 'run':
                $this->runServer($argument);
                break;

            case 'create:secret-key':
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
            
            case 'create:uploads':
                $this->createUploadsFolder();
                break;

            case 'clear:cache':
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
     * Execute the command and print its output.
     *
     * @param string $command
     * @return void
     */
    private function executeCommand($command)
    {
        $output = [];

        exec($command, $output);

        foreach ($output as $line) {
            print($line . PHP_EOL);
        }
    }

    /**
     * Default error message.
     * 
     * @return void
     */
    private function commandNotFound()
    {
        $message = <<< NotFound
        Invalid command.
        Type 'php sigma-cli help' command for help.
        NotFound;

        print($message . PHP_EOL);
    }

    /**
     * Print framework version.
     * 
     * @return void
     */
    private function version()
    {
        print("SigmaPHP framework version 0.1.0" . PHP_EOL);
    }
    
    /**
     * Print help menu.
     * 
     * @return void
     */
    private function help()
    {
        $helpContent = <<< HELP
        These are all available commands with SigmaPHP CLI Tool:

            clear:cache
                Clear views cache.
            create:controller {controller name}
                Create controller.
            create:migration {migration name}
                Create migration file.
            create:model {model name}
                Create model.
            create:secret-key
                Generate app secret key , and save it into .env file.
            create:seeder {seeder name}
                Create seeder file. 
            create:uploads
                Create uploads folder.
            create:view {view name}
                Create view.
            help
                Print all available commands (this menu).
            migrate
                Run migration files.
            rollback
                Rollback latest migration.
            run
                Run the app with PHP built in server on port 8888.
            seed
                Run seeders.
            test
                Run unit tests.
            version
                Print the current version of SigmaPHP Framework.

        Examples:
            - php sigma-cli version
            - php sigma-cli create:view my-view
            - php sigma-cli create:mode MyModel
            - php sigma-cli clear:cache
        HELP;

        print($helpContent . PHP_EOL);
    }

    /**
     * Run PHP built in server.
     * 
     * @param int $port
     * @return void
     */
    private function runServer($port = 8888)
    {
        $this->executeCommand("cd public; php -S localhost:$port");
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
            $envFile = file_get_Contents($this->config->getFullPath('.env'));
            
            if (strpos($envFile, 'APP_SECRET_KEY') !== FALSE) {
                $oldKey = substr(
                    $envFile, 
                    strpos($envFile, 'APP_SECRET_KEY') + 15, 
                    32
                );

                $envFile = str_replace($oldKey, $key, $envFile);
            } else {
                $envFile .= 'APP_SECRET_KEY="' . $key .'"';
            }

            file_put_contents($this->config->getFullPath('.env'), $envFile);
            echo "\033[32m App secret key was generated successfully" . PHP_EOL;
        } catch (\Exception $e) {
            echo "\033[31m $e" . PHP_EOL;
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
        $this->executeCommand(
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
        $this->executeCommand(
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
        $this->executeCommand($this->dbConsoleCommand("migrate"));
    }

    /**
     * Rollback the database.
     * 
     * @return void
     */
    private function rollback()
    {
        $this->executeCommand($this->dbConsoleCommand("rollback"));
    }

    /**
     * Seed the database.
     * 
     * @return void
     */
    private function seed()
    {
        $this->executeCommand($this->dbConsoleCommand("seed:run"));
    }

    /**
     * Create new file.
     * 
     * @param string $path
     * @param string $name
     * @param string $content
     * @return void
     */
    private function createFile($path, $name, $content)
    {
        try {
            file_put_contents($path . $name, $content);
            echo "\033[32m {$name} was created successfully" . PHP_EOL;
        } catch (\Exception $e) {
            echo "\033[31m $e" . PHP_EOL;
        }
    }

    /**
     * Create new controller.
     * 
     * @param string $controllerName
     * @return void
     */
    private function createController($controllerName)
    {
        $path = $this->config->getFullPath('src/Controllers/');

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

        $this->createFile($path, $controllerName . '.php', $content);
    }

    /**
     * Create new data model.
     * 
     * @param string $modelName
     * @return void
     */
    private function createModel($modelName)
    {
        $path = $this->config->getFullPath('src/Models/');

        $content = <<< MODEL_CONTENT
        <?php
        
        namespace SigmaPHP\Models;

        use SigmaPHP\Core\Models\BaseModel;

        class $modelName extends BaseModel
        {
            
        }
        MODEL_CONTENT;

        $this->createFile($path, $modelName . '.php', $content);
    }

    /**
     * Create new html view.
     * 
     * @param string $viewName
     * @return void
     */
    private function createView($viewName)
    {
        $path = $this->config->getFullPath('src/Views/');

        $this->createFile($path, $viewName . '.blade.php', '');
    }

    /**
     * Create uploads folder.
     * 
     * @return void
     */
    private function createUploadsFolder()
    {
        $storagePath = $this->config->getFullPath('storage/');
        $publicPath = $this->config->getFullPath('public/');

        try {
            if (!is_dir($storagePath . 'uploads')) {
                mkdir($storagePath . 'uploads');
            }

            if (!is_dir($publicPath . 'uploads')) {
                $result = $this->executeCommand(
                    "ln -s " . $storagePath . 'uploads/ ' .
                        $publicPath . 'uploads'
                );

                if ($result) {
                    echo "\033[32m Uploads folder was created successfully" .
                        PHP_EOL;
                }
            } else {
                echo "Uploads folder already exists" . PHP_EOL;
            }
        } catch (\Exception $e) {
            echo "\033[31m $e" . PHP_EOL;
        }
    }

    /**
     * Clear views cache.
     * 
     * @return void
     */
    private function clearCache()
    {
        $path = $this->config->getFullPath('storage/cache');
        
        try {
            if ($handle = opendir($path)) {
                while (($file = readdir($handle))) {
                    if (in_array($file, ['.', '..'])) continue;
                    unlink($path . '/'. $file);
                }
                
                closedir($handle);
            }

            echo "\033[32m Cache was cleared successfully" . PHP_EOL;
        } catch (\Exception $e) {
            echo "\033[31m $e" . PHP_EOL;
        }
    }

    /**
     * Run test units.
     * 
     * @return void
     */
    private function runTests()
    {
        $this->executeCommand("./vendor/bin/phpunit tests");
    }
}
