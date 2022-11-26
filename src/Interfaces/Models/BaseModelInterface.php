<?php

namespace SigmaPHP\Core\Interfaces\Models;

/**
 * Base Model Interface
 */
interface BaseModelInterface
{
    /**
     * Fetch all rows
     *
     * @return array
     */
    public function all();

    /**
     * Find row by id
     *
     * @param int $value
     * @return array
     */
    public function find($value);

    /**
     * Find row by field's value
     *
     * @param string $field
     * @param int $value
     * @return array
     */
    public function findBy($field, $value);

    /**
     * Create new row
     *
     * @param array|string $fields
     * @param array $values
     * @return bool
     */
    public function create($fields, $values);
    
    /**
     * Update row by id
     *
     * @param int $id
     * @param array|string $fields
     * @param array $values
     * @return bool
     */
    public function update($id, $fields, $values);
    
    /**
     * Delete row by id
     *
     * @param int $id
     * @return bool
     */
    public function delete($id);

    /**
     * Create custom query
     *
     * @param string $statement
     * @return mixed
     */
    public function query();
}