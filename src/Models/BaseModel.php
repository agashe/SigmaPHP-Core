<?php

namespace SigmaPHP\Core\Models;

use SigmaPHP\Core\Config\Config;
use SigmaPHP\Core\Interfaces\Models\BaseModelInterface;
use SigmaPHP\DB\Connectors\Connector;
use SigmaPHP\DB\ORM\Model;

/**
 * Base Model Class
 */
class BaseModel extends Model implements BaseModelInterface
{
    /**
     * @var SigmaPHP\Core\Config\Config $config
     */
    protected $config;

    /**
     * @var Connector $connector
     */
    protected $connector;

    /**
     * BaseModel Constructor
     */
    public function __construct(
        $dbConnection = null,
        $dbName = '',
        $values = [],
        $isNew = true
    ) {
        $this->config = new Config();
        $this->config->load();

        $dbConfigs = $this->config->get('database')['database_connection'];

        // create new DB connection
        $this->connector = new Connector($dbConfigs);
        $connection = $this->connector->connect();

        parent::__construct(
            $connection,
            $this->connector->getDatabaseName(),
            $values
        );
    }
}
