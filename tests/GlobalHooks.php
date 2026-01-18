<?php

use PHPUnit\Runner\BeforeFirstTestHook;
use PHPUnit\Runner\AfterLastTestHook;

/**
 * PHPUnit Hooks
 */
class GlobalHooks implements BeforeFirstTestHook, AfterLastTestHook
{
    /**
     * Execute before first test PHPUnit Hook.
     *
     * @return void
     */
    public function executeBeforeFirstTest(): void
    {
        $this->setScriptName();

        // create and initialize new Kernel instance
        new \SigmaPHP\Core\App\Kernel();

        $this->createEnvFile();
        $this->createConfigFiles();
        $this->createTemplates();
        $this->createRoutes();
        $this->createFileStorage();
    }

    /**
     * Execute after last test PHPUnit Hook.
     *
     * @return void
     */
    public function executeAfterLastTest(): void
    {
        $this->removeEnvFile();
        $this->removeConfigFiles();
        $this->removeTemplates();
        $this->removeRoutes();
        $this->removeFileStorage();
    }

    /**
     * Set SCRIPT_NAME for all test cases.
     *
     * @param string $scriptName
     * @return void
     */
    private function setScriptName($scriptName = '/index.php')
    {
        $_SERVER['SCRIPT_NAME'] = $scriptName;
    }

    /**
     * Create dummy .env file.
     *
     * @return void
     */
    private function createEnvFile()
    {
        if (!file_exists('.env')) {
            file_put_contents('.env', 'APP_ENV="development"');
        }
    }

    /**
     * Create dummy config directory and dummy config files.
     *
     * @return void
     */
    private function createConfigFiles()
    {
        if (!is_dir('config')) {
            mkdir('config');
        }

        if (!file_exists('config/app.php')) {
            file_put_contents(
                'config/app.php',
                '<?php return [' .
                    '"api" => ["version" => "1.0.0"],' .
                    '"views_path" => "templates/",' .
                    '"routes_path" => "routes/",' .
                    '"static_assets_route" => "static"' .
                '];'
            );
        }

        if (!file_exists('config/database.php')) {
            file_put_contents(
                'config/database.php',
                <<<CONFIG
                <?php

                return [
                    'path_to_migrations'  => '/database/migrations',
                    'path_to_seeders'     => '/database/seeders',
                    'path_to_models'      => '/src/Models',
                    'logs_table_name'     => 'db_logs',
                    'database_connection' => [
                        'host' => '{$GLOBALS['DB_HOST']}',
                        'name' => '{$GLOBALS['DB_NAME']}',
                        'user' => '{$GLOBALS['DB_USER']}',
                        'pass' => '{$GLOBALS['DB_PASS']}',
                        'port' => '{$GLOBALS['DB_PORT']}',
                    ]
                ];
                CONFIG
            );
        }
    }

    /**
     * Create dummy templates.
     *
     * @return void
     */
    private function createTemplates()
    {
        if (!is_dir('templates')) {
            mkdir('templates');
        }

        if (!is_dir('cache')) {
            mkdir('cache');
        }

        if (!file_exists('templates/index.template.html')) {
            file_put_contents(
                'templates/index.template.html',
                '<h1>hello {{ $name }}</h1>'
            );
        }
    }

    /**
     * Create dummy routes file.
     *
     * @return void
     */
    private function createRoutes()
    {
        if (!is_dir('routes')) {
            mkdir('routes');
        }

        if (!file_exists('web.php')) {
            file_put_contents(
                'routes/web.php',
                '<?php return [["path" => "/test", "name" => "test"]];'
            );
        }
    }

    /**
     * Create dummy file storage.
     *
     * @return void
     */
    private function createFileStorage()
    {
        if (!is_dir('uploads')) {
            mkdir('uploads');
        }

        if (!file_exists('file12345')) {
            file_put_contents('file12345', 'hello world');
        }

        if (!file_exists('uploads/book.txt')) {
            file_put_contents('uploads/book.txt', 'My Book');
        }
    }

    /**
     * Remove the dummy .env file.
     *
     * @return void
     */
    private function removeEnvFile()
    {
        if (file_exists('.env')) {
            unlink('.env');
        }
    }

    /**
     * Remove the dummy config files.
     *
     * @return void
     */
    private function removeConfigFiles()
    {
        if (file_exists('config/app.php')) {
            unlink('config/app.php');
        }

        if (file_exists('config/database.php')) {
            unlink('config/database.php');
        }

        if (is_dir('config')) {
            rmdir('config');
        }
    }

    /**
     * Remove the dummy templates and cache.
     *
     * @return void
     */
    private function removeTemplates()
    {
        if (file_exists('templates/index.template.html')) {
            unlink('templates/index.template.html');
        }

        if (is_dir('templates')) {
            rmdir('templates');
        }

        $cacheFiles = glob('cache/*');

        foreach ($cacheFiles as $cacheFile) {
            if (file_exists($cacheFile)) {
                unlink($cacheFile);
            }
        }

        if (is_dir('cache')) {
            rmdir('cache');
        }
    }

    /**
     * Remove the dummy routes file.
     *
     * @return void
     */
    private function removeRoutes()
    {
        if (file_exists('routes/web.php')) {
            unlink('routes/web.php');
        }

        if (is_dir('routes')) {
            rmdir('routes');
        }
    }

    /**
     * Remove the dummy file storage.
     *
     * @return void
     */
    private function removeFileStorage()
    {
        if (file_exists('uploads/test.txt')) {
            unlink('uploads/test.txt');
        }

        if (file_exists('file12345')) {
            unlink('file12345');
        }

        if (file_exists('uploads/book.txt')) {
            unlink('uploads/book.txt');
        }

        if (is_dir('uploads')) {
            rmdir('uploads');
        }
    }
}
