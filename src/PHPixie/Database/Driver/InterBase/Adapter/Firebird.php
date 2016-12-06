<?php
namespace PHPixie\Database\Driver\InterBase\Adapter;

/**
 * Class Firebird
 * @package PHPixie\Database\Driver\InterBase\Adapter
 */
class Firebird extends \PHPixie\Database\Driver\PDO\Adapter
{
    /**
     * @var \PHPixie\Database\Driver\PDO\Connection
     */
    protected $connection;

    /**
     * @param bool $withDatabase
     * @return string
     */
    public function dsn($withDatabase = true)
    {
        if($connection = $this->config->get('connection')) {
            $dsn = $connection;
        } else {
            $dsn = 'firebird:dbname=';
            if($host = $this->config->get('host')) {
                $dsn .= $this->config->get('host');
                if($port = $this->config->get('port')) {
                    $dsn .= '/' . $port;
                }
                $dsn .= ':';
            }
            if($withDatabase) {
                $dsn .= $this->config->getRequired('database');
            }
        }

        return $dsn;
    }

    /**
     * @param string $table
     * @return array
     */
    public function listColumns($table)
    {
        /** @var \PHPixie\Database\Driver\PDO\Result $result */
        $result = $this->connection->execute('SELECT rdb$field_name FROM rdb$relation_fields WHERE rdb$relation_name=\'' . $table . '\';');

        return $result->getField('Field');
    }

    /**
     * @return string
     */
    public function name()
    {
        return 'firebird';
    }

}

