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
        // set SCRIPT_NAME for all test cases
        $_SERVER['SCRIPT_NAME'] = '/index.php';

        // create and initialize new Kernel instance
        new \SigmaPHP\Core\App\Kernel();

        // create dummy .env file
        if (!file_exists('.env')) {
            file_put_contents('.env', 'APP_ENV="development"');
        }

        // create dummy config directory and dummy config files
        if (!is_dir('config')) {
            mkdir('config');
        }

        if (!file_exists('config/app.php')) {
            file_put_contents(
                'config/app.php',
                '<?php return [' .
                    '"api" => ["version" => "1.0.0"],' .
                    '"views_path" => "templates/",' .
                    '"routes_path" => "routes/"' .
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

        // create dummy templates
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

        // create dummy routes file
        if (!is_dir('routes')) {
            mkdir('routes');
        }

        if (!file_exists('web.php')) {
            file_put_contents(
                'routes/web.php',
                '<?php return [["path" => "/test", "name" => "test"]];'
            );
        }

        // create dummy file storage
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
     * Execute after last test PHPUnit Hook.
     *
     * @return void
     */
    public function executeAfterLastTest(): void
    {
        // remove the dummy .env file
        if (file_exists('.env')) {
            unlink('.env');
        }

        // remove the testing config files
        if (file_exists('config/app.php')) {
            unlink('config/app.php');
        }

        if (file_exists('config/database.php')) {
            unlink('config/database.php');
        }

        if (is_dir('config')) {
            rmdir('config');
        }

        // remove the dummy templates and cache
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

        // remove the dummy routes file
        if (file_exists('routes/web.php')) {
            unlink('routes/web.php');
        }

        if (is_dir('routes')) {
            rmdir('routes');
        }

        // remove the dummy file storage
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
