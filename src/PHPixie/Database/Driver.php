<?php

namespace PHPixie\Database;

use PHPixie\Database;

/**
 * Class Driver
 * @package PHPixie\Database
 */
abstract class Driver
{
    /**
     * @var Database
     */
    protected $database;
    /**
     * @var mixed
     */
    protected $conditions;
    /**
     * @var array
     */
    protected $parsers =  array();
    /**
     * @var array
     */
    protected $connections = array();

    /**
     * Driver constructor.
     * @param $database Database
     */
    public function __construct($database)
    {
        $this->database = $database;
    }

    /**
     * @return mixed
     */
    public function conditions()
    {
        if ($this->conditions === null)
            $this->conditions = $this->buildConditions();

        return $this->conditions;
    }

    /**
     * @param $connectionName string
     * @return Parser
     */
    public function parser($connectionName)
    {
        if (!isset($this->parsers[$connectionName]))
            $this->parsers[$connectionName] = $this->buildParserInstance($connectionName);

        return $this->parsers[$connectionName];
    }

    /**
     * @param string $type
     * @param string $connectionName
     * @return mixed
     */
    public function query($type = 'select', $connectionName = 'default')
    {
        $connection = $this->database->get($connectionName);
        $parser     = $this->parser($connectionName);
        $builder    = $this->queryBuilder();

        return $this->buildQuery($type, $connection, $parser, $builder);
    }

    /**
     * @return mixed
     */
    abstract public function queryBuilder();

    /**
     * @return mixed
     */
    abstract public function buildConditions();

    /**
     * @param $name string
     * @param $config array
     * @return mixed
     */
    abstract public function buildConnection($name, $config);

    /**
     * @param $connectionName string
     * @return mixed
     */
    abstract public function buildParserInstance($connectionName);

    /**
     * @param $type string
     * @param $connection
     * @param $parser
     * @param $builder
     * @return mixed
     */
    abstract public function buildQuery($type, $connection, $parser, $builder);

    /**
     * @param $cursor
     * @return mixed
     */
    abstract public function result($cursor);
}
