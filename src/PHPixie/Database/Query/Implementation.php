<?php

namespace PHPixie\Database\Query;

abstract class Implementation implements \PHPixie\Database\Query
{
    protected $database;
    protected $conditions;
    protected $connection;
    protected $parser;
    protected $config;
    protected $type;

    protected $data = null;
    protected $fields = array();

    public function __construct($database, $connection, $parser, $config, $type)
    {
        $this->database         = $database;
        $this->connection = $connection;
        $this->parser     = $parser;
        $this->config     = $config;
        $this->type($type);
    }

    public function type($type)
    {
        $this->type = $type;

        return $this;
    }

    public function getType()
    {
        return $this->type;
    }

    public function data($data)
    {
        $this->data = $data;

        return $this;
    }

    public function getData()
    {
        return $this->data;
    }

    public function fields($fields)
    {
        if ($fields !== null && !is_array($fields))
            throw new \PHPixie\Database\Exception\Builder("Field list must either be an array or NULL");

        $this->fields = $fields;

        return $this;
    }

    public function getFields()
    {
        return $this->fields;
    }


    public function parse()
    {
        return $this->parser->parse($this);
    }

    abstract public function execute();

}
