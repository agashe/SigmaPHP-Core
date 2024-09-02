<?php

use PHPUnit\Framework\TestCase;
use SigmaPHP\Core\Tests\Models\TestModel;

/**
 * Base Model Test
 *
 * Please note : for this test unit we will need a real
 * database connection with empty test database to run our tests.
 * I know this's not the best idea , but i personally believe it's
 * more reliable.
 */
class BaseModelTest extends TestCase
{
    /**
     * @var array $dbConfigs
     */
    private $dbConfigs;

    /**
     * @var Model $model
     */
    private $model;

    /**
     * BaseModelTest SetUp
     *
     * @return void
     */
    public function setUp(): void
    {
        // add your database configs to phpunit.xml
        // and to database.config.php for the SigmaPHP-DB
        // package to work properly
        $this->dbConfigs = [
            'host' => $GLOBALS['DB_HOST'],
            'name' => $GLOBALS['DB_NAME'],
            'user' => $GLOBALS['DB_USER'],
            'pass' => $GLOBALS['DB_PASS'],
            'port' => $GLOBALS['DB_PORT'],
        ];

        // create test table
        $this->createTestTable('test_models');

        // copy testing database config file
        if (!is_dir('config')) {
            mkdir('config');
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

        // create new test model instance
        $this->model = new TestModel();
    }

    /**
     * DbTestCase TearDown
     *
     * @return void
     */
    public function tearDown(): void
    {
        // remove the testing config file
        if (file_exists('config/database.php')) {
            unlink('config/database.php');
        }

        if (is_dir('config')) {
            rmdir('config');
        }

        $this->dropTestTable('test_models');
    }

    /**
     * Connect to database.
     *
     * @return \PDO
     */
    private function connectToDatabase()
    {
        return new \PDO(
            "mysql:host={$this->dbConfigs['host']};
            dbname={$this->dbConfigs['name']}",
            $this->dbConfigs['user'],
            $this->dbConfigs['pass']
        );
    }

    /**
     * Create test table.
     *
     * @param string $name
     * @return void
     */
    private function createTestTable($name = 'test')
    {
        $testTable = $this->connectToDatabase()->prepare("
            CREATE TABLE IF NOT EXISTS {$name} (
                id INT(11) AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(25),
                email VARCHAR(50)
            );
        ");

        $testTable->execute();
    }

    /**
     * Drop test table.
     *
     * @param string $name
     * @return void
     */
    private function dropTestTable($name = 'test')
    {
        $testTable = $this->connectToDatabase()->prepare("
            Drop TABLE IF EXISTS {$name};
        ");

        $testTable->execute();
    }

    /**
     * Insert dummy data into test table.
     *
     * @return void
     */
    private function populateTestTable()
    {
        $addTestData = $this->connectToDatabase()->prepare("
            INSERT INTO test_models
                (name, email)
            VALUES
                ('test1', 'test1@test.local'),
                ('foo2', 'test2@test.local'),
                ('test3', 'test3@test.local'),
                ('foo4', 'test4@test.local'),
                ('test5', 'test5@test.local');
        ");

        $addTestData->execute();
    }

    /**
     * Test insert data.
     *
     * @runInSeparateProcess
     * @return void
     */
    public function testInsertData()
    {
        // first : using the create method
        $testCreateModel = $this->model->create([
            'name' => 'test1',
            'email' => 'test1@test.com',
        ]);

        // second : using the save method
        $testSaveModel = new TestModel();
        $testSaveModel->name = 'test2';
        $testSaveModel->email = 'test1@test.com';
        $testSaveModel->save();

        $testTable = $this->connectToDatabase()->prepare("
            SELECT * FROM test_models;
        ");

        $testTable->execute();

        $this->assertEquals(2, count($testTable->fetchAll()));
    }

    /**
     * Test update data.
     *
     * @runInSeparateProcess
     * @return void
     */
    public function testUpdateData()
    {
        $this->populateTestTable();

        $testUpdateModel = $this->model->find(1);
        $testUpdateModel->name = 'updated_name';
        $testUpdateModel->save();

        $testTable = $this->connectToDatabase()->prepare("
            SELECT * FROM test_models;
        ");

        $testTable->execute();

        $this->assertEquals('updated_name', $testTable->fetch()['name']);
    }

    /**
     * Test delete data.
     *
     * @runInSeparateProcess
     * @return void
     */
    public function testDeleteData()
    {
        $this->populateTestTable();

        $testDeleteModel = $this->model->find(1);
        $testDeleteModel->delete();

        $testTable = $this->connectToDatabase()->prepare("
            SELECT * FROM test_models;
        ");

        $testTable->execute();

        $this->assertEquals(4, count($testTable->fetchAll()));
    }

    /**
     * Test fetch all data.
     *
     * @runInSeparateProcess
     * @return void
     */
    public function testFetchAllData()
    {
        $this->populateTestTable();

        $testFetchAllModels = $this->model->all();

        $this->assertEquals(5, count($testFetchAllModels));
    }

    /**
     * Test find data by id.
     *
     * @runInSeparateProcess
     * @return void
     */
    public function testFindDataById()
    {
        $this->populateTestTable();

        $testFetchModel = $this->model->find(3);

        $this->assertEquals('test3', $testFetchModel->name);
    }

    /**
     * Test find data by field.
     *
     * @runInSeparateProcess
     * @return void
     */
    public function testFindDataByField()
    {
        $this->populateTestTable();

        $testFetchModel = $this->model->findBy('email', 'test3@test.local');

        $this->assertEquals('test3', $testFetchModel->name);
    }

    /**
     * Test query data.
     *
     * @runInSeparateProcess
     * @return void
     */
    public function testQueryData()
    {
        $this->populateTestTable();

        $testModelsCount = $this->model
            ->where('name', 'like', 'foo%')
            ->count();

        $this->assertEquals(2, $testModelsCount);
    }
}
