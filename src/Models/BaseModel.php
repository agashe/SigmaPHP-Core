<?php

namespace SigmaPHP\Core\Models;

use SigmaPHP\Core\Interfaces\Models\BaseModelInterface;
use Envms\FluentPDO\Query as Query;
use Doctrine\Inflector\InflectorFactory;

/**
 * Base Model
 */
class BaseModel implements BaseModelInterface
{
    /**
     * @var object $connection
     */
    private $connection;

    /**
     * @var object $db
     */
    private $db;

    /**
     * @var string $table
     */
    protected $table;

    /**
     * @var array $fields
     */
    protected $fields;

    /**
     * BaseModel Constructor
     */
    public function __construct($dbConfigs = [])
    {
        // validate configs
        if (empty($dbConfigs)) {
            throw new \Exception("Error : no database configs were provided");
        }
        
        foreach (['host', 'name', 'user', 'pass'] as $config) {
            if (!isset($dbConfigs['db_'.$config]) || 
                empty($dbConfigs['db_'.$config])) {
                throw new \Exception("Error : missing database {$config}");
            }
        }

        // create new PDO connection
        $this->connection = new \PDO(
            "mysql:host={$dbConfigs['db_host']};
            dbname={$dbConfigs['db_name']}",
            $dbConfigs['db_user'],
            $dbConfigs['db_pass']
        );

        // set table name if it wasn't provided
        if (empty($this->table)) {
            $this->table = $this->createTableName(get_called_class());
        }
        
        // check if table exists
        if (!$this->checkTableExists($dbConfigs['db_name'])) {
            throw new \Exception(
                "Error : table {$this->table} doesn't exist"
            );
        }

        // fetch fields
        if (empty($this->fields)) {
            $this->fields = $this->fetchTableFields($dbConfigs['db_name']);
        }

        // create new FluentPDO instance
        $this->db = new Query($this->connection);
    }

    /**
     * Create table name.
     *
     * @param string $className
     * @return string
     */
    protected function createTableName($className)
    {
        $tableName = substr(
            $className, 
            (-1 * (strlen($className) - strrpos($className, '\\') - 1))
        );            

        $inflector = InflectorFactory::create()->build();
        return $inflector->pluralize($inflector->tableize($tableName));
    }

    /**
     * Check if table exists.
     *
     * @param string $dbName
     * @return bool
     */
    protected function checkTableExists($dbName)
    {
        $tableExists = $this->connection->prepare("
            SELECT
                TABLE_NAME
            FROM 
                INFORMATION_SCHEMA.TABLES
            WHERE 
                TABLE_SCHEMA = '{$dbName}'
            AND
                TABLE_NAME = '{$this->table}'
        ");

        $tableExists->execute();

        return ($tableExists->fetch() != false);
    }

    /**
     * Fetch table fields.
     *
     * @param string $dbName
     * @return array
     */
    protected function fetchTableFields($dbName)
    {
        $tableFields = $this->connection->prepare("
        SELECT
            GROUP_CONCAT(COLUMN_NAME) AS FIELDS
        FROM 
            INFORMATION_SCHEMA.COLUMNS
        WHERE 
            TABLE_SCHEMA = '{$dbName}'
        AND
            TABLE_NAME = '{$this->table}'
        ");

        $tableFields->execute();
        $fields = explode(',', $tableFields->fetchAll()[0]['FIELDS']);

        // remove the id field to avoid
        // errors in queries
        unset($fields[0]);

        return array_values($fields);
    }

    /**
     * Fetch all rows.
     *
     * @return array
     */
    final public function all()
    {
        return $this->db->from($this->table)->fetchAll();
    }

    /**
     * Find row by id.
     *
     * @param int $id
     * @return array
     */
    final public function find($id)
    {
        return $this->db->from($this->table, $id)->fetch();
    }

    /**
     * Find row by field's value.
     *
     * @param string $field
     * @param int $value
     * @return array
     */
    final public function findBy($field, $value)
    {
        return $this->db->from($this->table)->where($field, $value)->fetch();
    }

    /**
     * Validate the passed parameters to create and update.
     *
     * @param string $field
     * @param int $value
     * @return array the valid params
     */
    private function validateParams($fields, $values)
    {
        if (empty($this->fields) || !is_array($fields)) {
            throw new \Exception(
                "Error : No fields were provided to the model"
            );
        } 
        else if ($fields == '*') {
            $fields = $this->fields;
        }
        
        if (is_array($fields) && is_array($values) && 
            (count($fields) != count($values))
        ) {
            throw new \Exception("Error : Fields and values are mismatched");
        }
        
        return array_combine($fields, $values);
    }

    /**
     * Create new row.
     *
     * @param array|string $fields
     * @param array $values
     * @return bool
     */
    final public function create($fields, $values)
    {
        return $this->db->insertInto(
            $this->table,
            $this->validateParams($fields, $values)
        )->execute();
    }
    
    /**
     * Update row by id.
     *
     * @param int $id
     * @param array|string $fields
     * @param array $values
     * @return bool
     */
    final public function update($id, $fields, $values)
    {
        if (empty($id)) {
            throw new \Exception(
                "Error : No id was provided for update"
            );
        }

        return $this->db->update(
            $this->table,
            $this->validateParams($fields, $values),
            $id
        )->execute();
    }
    
    /**
     * Delete row by id.
     *
     * @param int $id
     * @return bool
     */
    final public function delete($id)
    {
        if (empty($id)) {
            throw new \Exception(
                "Error : No id was provided for delete"
            );
        }

        return $this->db->deleteFrom($this->table, $id)->execute();
    }

    /**
     * Create custom query.
     *
     * @param string $statement
     * @return mixed
     */
    final public function query()
    {
        return $this->db->from($this->table);
    }
}