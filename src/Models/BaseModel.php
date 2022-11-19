<?php

namespace SigmaPHP\Models;

use Envms\FluentPDO\Query as Query;

/**
 * Base Model
 */
class Model
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
     * @var bool $tableExists
     */
    private $tableExists;

    /**
     * @var string $table
     */
    protected $table;

    /**
     * @var array $fields
     */
    protected $fields;

    /**
     * Model Constructor
     */
    public function __construct()
    {
        // create new PDO connection
        $this->connection = new \PDO(
            "mysql:host={$_ENV['DB_HOST']};
            dbname={$_ENV['DB_NAME']}",
            $_ENV['DB_USER'],
            $_ENV['DB_PASS'],
        );

        // set table name if wasn't provided
        if (empty($this->table)) {
            $class = get_called_class();
            
            $tableName = substr(
                $class, 
                (-1 * (strlen($class) - strrpos($class, '\\') - 1))
            );            

            $this->table = (strtolower($tableName) . 's');
        }
        
        // check if table exists
        if (!$this->tableExists) {
            $tableExists = $this->connection->prepare("
                SELECT
                    TABLE_NAME
                FROM 
                    INFORMATION_SCHEMA.TABLES
                WHERE 
                    TABLE_SCHEMA = '{$_ENV['DB_NAME']}'
                AND
                    TABLE_NAME = '{$this->table}'
            ");

            $tableExists->execute();

            if ($tableExists->fetch() == false)
                throw new \Exception("Error : table {$this->table} doesn't exist");
            else
                $this->tableExists = true;
        }

        // fetch fields
        if (empty($this->fields)) {
            $tableFields = $this->connection->prepare("
            SELECT
                GROUP_CONCAT(COLUMN_NAME) AS FIELDS
            FROM 
                INFORMATION_SCHEMA.COLUMNS
            WHERE 
                TABLE_SCHEMA = '{$_ENV['DB_NAME']}'
            AND
                TABLE_NAME = '{$this->table}'
            ");

            $tableFields->execute();
            $this->fields = explode(',', $tableFields->fetchAll()[0]['FIELDS']);

            // remove the id field to avoid
            // errors in queries
            unset($this->fields[0]);
        }

        // create new FluentPDO instance
        $this->db = new Query($this->connection);
    }

    /**
     * Fetch all rows
     *
     * @return array
     */
    final public function all()
    {
        return $this->db->from($this->table)->fetchAll();
    }

    /**
     * Find row by id
     *
     * @param int $value
     * @return array
     */
    final public function find($value)
    {
        return $this->db->from($this->table, $value)->fetch();
    }

    /**
     * Find row by field's value
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
        if ($fields == '*') {
            if (empty($this->fields))
                throw new \Exception("Error : No fields were provided to the model");
            else
                $fields = $this->fields;
        }
        
        if (is_array($fields) && is_array($values) && 
            (count($fields) != count($values)))
            throw new \Exception("Error : Fields and values are mismatched");
        
        return array_combine($fields, $values);
    }

    /**
     * Create new row
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
     * Update row by id
     *
     * @param int $id
     * @param array|string $fields
     * @param array $values
     * @return bool
     */
    final public function update($id, $fields, $values)
    {        
        return $this->db->update(
            $this->table,
            $this->validateParams($fields, $values),
            $id
        )->execute();
    }
    
    /**
     * Delete row by id
     *
     * @param int $id
     * @return bool
     */
    final public function delete($id)
    {
        return $this->db->deleteFrom($this->table, $id)->execute();
    }

    /**
     * Create custom query
     *
     * @param string $statement
     * @return mixed
     */
    final public function query()
    {
        return $this->db->from($this->table);
    }
}