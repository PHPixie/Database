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
     * @var Conditions
     */
    protected $conditions;

    /**
     * @var Parser[]
     */
    protected $parsers =  array();
    /**
     * @var Connection[] ???
     */
    protected $connections = array();

    /**
     * Driver constructor.
     * @param Database $database
     */
    public function __construct($database)
    {
        $this->database = $database;
    }

    /**
     * @return \Psr\Log\LoggerInterface|null
     */
    public function logger()
    {
        return $this->database->logger();
    }
    
    /**
     * @return Conditions
     */
    public function conditions()
    {
        if ($this->conditions === null)
            $this->conditions = $this->buildConditions();

        return $this->conditions;
    }

    /**
     * @param string $connectionName
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
     * @return Query\Implementation
     */
    public function query($type = 'select', $connectionName = 'default')
    {
        $connection = $this->database->get($connectionName);
        $parser     = $this->parser($connectionName);
        $builder    = $this->queryBuilder();

        return $this->buildQuery($type, $connection, $parser, $builder);
    }

    /**
     * @return Query\Implementation\Builder
     */
    abstract public function queryBuilder();

    /**
     * @return Conditions
     */
    abstract public function buildConditions();

    /**
     * @param string $name
     * @param \PHPixie\Slice\Type\ArrayData\Slice $config
     * @return Connection
     */
    abstract public function buildConnection($name, $config);

    /**
     * @param string $connectionName
     * @return mixed
     */
    abstract public function buildParserInstance($connectionName);

    /**
     * @param string $type
     * @param Connection $connection
     * @param Parser $parser
     * @param Query\Implementation\Builder $builder
     * @return Query\Implementation
     */
    abstract public function buildQuery($type, $connection, $parser, $builder);

    /**
     * @param $cursor
     * @return Result
     */
    abstract public function result($cursor);
}
