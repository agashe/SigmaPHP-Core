<?php

namespace SigmaPHP\Core\Console;

use PassGen\PassGen;
use EnvParser\Parser;
use SigmaPHP\Core\Config\Config;
use SigmaPHP\Core\Exceptions\DirectoryNotFoundException;
use SigmaPHP\Core\Exceptions\FileNotFoundException;

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
                $this->runServer($argument ?? 8888);
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

            case 'drop':
                $this->drop();
                break;

            case 'fresh':
                $this->fresh();
                break;

            case 'migrate':
                $this->migrate($argument);
                break;

            case 'rollback':
                $this->rollback($argument);
                break;

            case 'seed':
                $this->seed($argument);
                break;
            
            case 'truncate':
                $this->truncate();
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
     * Print text to terminal and support colors.
     * 
     * @param string $message
     * @param string $type
     * @return void
     */
    private function output($message, $type = '')
    {
        // check if terminal supports color
        if (exec('tput colors') == -1 || 
            !stream_isatty(STDOUT) || 
            isset($_SERVER['NO_COLOR'])
        ) {
            $type = '';
        }

        $colors = [
            'error'   => '31',
            'success' => '32',
            'warning' => '33',
            'info'    => '34',
        ];

        // check if the message's type is valid
        if (!empty($type) && !isset($colors[$type])) {
            throw new \InvalidArgumentException(
                "The message's type '{$type}' doesn't exist"
            );
        }

        if (!empty($type)) {
            $message = "\033[{$colors[$type]}m" . $message . "\033[0m";
        }

        print($message . PHP_EOL);
    }
    
    /**
     * Execute the command and print its output.
     *
     * @param string $command
     * @param bool $printOutput
     * @return bool
     */
    private function executeCommand($command, $printOutput = false)
    {
        $output = [];
        $result = false;

        exec($command . ($printOutput ? '' : ' 2>/dev/null'), $output, $result);

        if ($printOutput) {
            foreach ($output as $line) {
                $this->output($line);
            }
        }

        return ($result == 0);
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

        $this->output($message);
    }

    /**
     * Print framework version.
     * 
     * @return void
     */
    private function version()
    {
        $this->output("SigmaPHP framework version 0.1.0");
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
                Create model. This command will generate
                in addition a new migration file automatically.
            create:secret-key
                Generate app secret key , and save it into .env file.
            create:seeder {seeder name}
                Create seeder file. 
            create:uploads
                Create uploads folder.
            create:view {view name}
                Create view.
            drop
                Drop all tables in the database.
            fresh
                Drop all tables in the database. then will run
                all migrations and seed the database.
            help
                Print all available commands (this menu).
            migrate {migration name}
                Run migration/s files.
            rollback {date}
                Rollback latest migration. or choose specific date
                to rollback to.
            run
                Run the app with PHP built in server on port 8888.
            seed {seeder name}
                Run seeder/s.
            test
                Run unit tests.
            truncate
                Delete the data in all tables.
            version
                Print the current version of SigmaPHP Framework.

        Examples:
            - php sigma-cli version
            - php sigma-cli create:view my-view
            - php sigma-cli create:mode MyModel
            - php sigma-cli clear:cache
        HELP;

        $this->output($helpContent);
    }

    /**
     * Run PHP built-in server.
     * 
     * @param int $port
     * @return void
     */
    private function runServer($port = 8888)
    {
        // check if server 
        if (!empty($port) && !preg_match('/[0-9]{4}/', $port)) {
            throw new \InvalidArgumentException(
                "Invalid port number {$port}"
            );
        }

        // check that pubic/ dir is exist
        if (!file_exists('public/')) {
            throw new DirectoryNotFoundException(
                "The public/ directory doesn't exist"
            );
        }

        $this->executeCommand("php -S localhost:$port -t public/", true);
    }

    /**
     * Generate secure key.
     * 
     * @return void
     */
    private function generateAppSecretKey()
    {
        // check if the .env file is exist
        if (!file_exists('.env')) {
            throw new FileNotFoundException(
                "No .env file was found"
            );
        }

        $passGen = new PassGen(32);
        $key = $passGen->create();

        // replace invalid symbols from the generated key
        $key  = str_replace('#', 'A', $key);
        $key  = str_replace('\'', 'B', $key);
        $key  = str_replace('"', 'C', $key);

        try {
            $envFile = file_get_Contents(root_path('.env'));
            
            if (!empty(env('APP_SECRET_KEY'))) {
                $envFile = str_replace(env('APP_SECRET_KEY'), $key, $envFile);
            } 
            else if (!strpos($envFile, 'APP_SECRET_KEY')) {
                $envFile .= 'APP_SECRET_KEY=' . $key;
            }
            else {
                $envFile = str_replace(
                    'APP_SECRET_KEY=', 
                    'APP_SECRET_KEY=' . $key, 
                    $envFile
                );
            }

            file_put_contents(root_path('.env'), $envFile);
            
            $this->output(
                'App secret key was generated successfully',
                'success'
            );
        } catch (\Exception $e) {
            $this->output($e, 'error');
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
            "./vendor/bin/sigma-db {$command} " .
            "--config=" . root_path('config/database.php');
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
            $this->dbConsoleCommand("create:migration {$fileName}")
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
            $this->dbConsoleCommand("create:seeder {$seederName}")
        );
    }

    /**
     * Drop all tables in the database.
     * 
     * @return void
     */
    private function drop()
    {
        $this->executeCommand($this->dbConsoleCommand("drop"));
    }

    /**
     * Drop all tables in the database then migrate and seed.
     * 
     * @return void
     */
    private function fresh()
    {
        $this->executeCommand($this->dbConsoleCommand("fresh"));
    }
    
    /**
     * Migrate the database.
     * 
     * @param string $migrationName
     * @return void
     */
    private function migrate($migrationName = '')
    {
        $this->executeCommand(
            $this->dbConsoleCommand("migrate $migrationName")
        );
    }

    /**
     * Rollback the database.
     * 
     * @param string $date
     * @return void
     */
    private function rollback($date = '')
    {
        $this->executeCommand($this->dbConsoleCommand("rollback $date"));
    }

    /**
     * Seed the database.
     * 
     * @param string $seederName
     * @return void
     */
    private function seed($seederName = '')
    {
        $this->executeCommand($this->dbConsoleCommand("seed:run $seederName"));
    }

    /**
     * Truncate the database.
     * 
     * @return void
     */
    private function truncate()
    {
        $this->executeCommand($this->dbConsoleCommand("truncate"));
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
            // check if the files is not already exist
            if (file_exists($path . $name)) {
                $this->output("{$name} already exists", 'warning');
                return;
            }

            file_put_contents($path . $name, $content);
            $this->output("{$name} was created successfully", 'success');
        } catch (\Exception $e) {
            $this->output($e, 'error');
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
        $path = root_path(config('app.controllers_path'));
        
        // check that controllers path does exist
        if (!file_exists($path)) {
            throw new DirectoryNotFoundException(
                "The path to controllers {$path} doesn't exist"
            );
        }

        // check that controller's name is not empty
        if (empty($controllerName)) {
            throw new \InvalidArgumentException(
                "Controller's name can't be empty"
            );
        }

        $this->createFile($path, $controllerName . '.php', str_replace(
            '$controllerName',
            $controllerName,
            file_get_contents(__DIR__ . '/templates/controller.php.dist')
        ));
    }

    /**
     * Create new data model.
     * 
     * @param string $modelName
     * @return void
     */
    private function createModel($modelName)
    {
        $this->executeCommand(
            $this->dbConsoleCommand("create:model $modelName")
        );
    }

    /**
     * Create new html view.
     * 
     * @param string $viewName
     * @return void
     */
    private function createView($viewName)
    {
        $path = root_path(config('app.views_path'));

        // check that views path does exist
        if (!file_exists($path)) {
            throw new DirectoryNotFoundException(
                "The path to views {$path} doesn't exist"
            );
        }

        // check that view's name is not empty
        if (empty($viewName)) {
            throw new \InvalidArgumentException(
                "View's name can't be empty"
            );
        }

        $this->createFile($path, $viewName . '.template.html', '');
    }

    /**
     * Create uploads folder.
     * 
     * @return void
     */
    private function createUploadsFolder()
    {
        $storagePath = root_path('storage/');
        $publicPath = root_path('public/');

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
                    $this->output(
                        'Uploads folder was created successfully', 
                        'success'
                    );
                }
            } else {
                $this->output('Uploads folder already exists', 'warning');
            }
        } catch (\Exception $e) {
            $this->output($e, 'error');
        }
    }

    /**
     * Clear views cache.
     * 
     * @return void
     */
    private function clearCache()
    {
        $path = root_path('storage/cache');

        // check that storage/cache/ is exist
        if (!file_exists('storage/cache/')) {
            throw new DirectoryNotFoundException(
                "The directory storage/cache/ doesn't exist"
            );
        }

        try {
            if ($handle = opendir($path)) {
                while (($file = readdir($handle))) {
                    if (in_array($file, ['.', '..'])) continue;
                    unlink($path . '/'. $file);
                }
                
                closedir($handle);
            }

            $this->output('Cache was cleared successfully', 'success');
        } catch (\Exception $e) {
            $this->output($e, 'error');
        }
    }

    /**
     * Run test units.
     * 
     * @return void
     */
    private function runTests()
    {
        // check that tests/ is exist
        if (!file_exists('tests/')) {
            throw new DirectoryNotFoundException(
                "The directory tests/ doesn't exist"
            );
        }

        $this->executeCommand("./vendor/bin/phpunit tests");
    }
}
