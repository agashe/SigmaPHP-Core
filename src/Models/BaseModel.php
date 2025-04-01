<?php

namespace SigmaPHP\Core\Models;

use SigmaPHP\Core\Interfaces\Models\BaseModelInterface;
use SigmaPHP\DB\ORM\Model;

/**
 * Core Base Model Class
 */
class BaseModel extends Model implements BaseModelInterface
{
    /**
     * BaseModel Constructor
     * 
     * @param Connector $dbConnection
     * @param string $dbName
     * @param array $values
     * @param bool $isNew
     */
    public function __construct(
        $dbConnection = null,
        $dbName = '',
        $values = [],
        $isNew = true
    ) {
        parent::__construct(
            container('db'),
            config('database.database_connection.name'),
            $values
        );
    }
}
