<?php

namespace PHPixie\Database\Driver\Mongo;

class Parser extends \PHPixie\Database\Parser
{
    protected $conditionsParser;

    public function __construct($database, $driver, $config, $conditionsParser)
    {
        parent::__construct($database, $driver, $config);
        $this->conditionsParser = $conditionsParser;
    }

    public function parse($query)
    {
        $runner = $this->driver->runner();;
        switch ($query->type()) {
            case 'select':
                return $this->selectQuery($query, $runner);
            case 'selectSingle':
                return $this->selectSingleQuery($query, $runner);
            case 'insert':
                return $this->insertQuery($query, $runner);
            case 'update':
                return $this->updateQuery($query, $runner);
            case 'delete':
                return $this->deleteQuery($query, $runner);
            case 'count':
                return $this->countQuery($query, $runner);
            default:
                throw new \PHPixie\Database\Exception\Parser("Query type '{$query->type()}' is not supported");
        }
    }

    protected function selectQuery($query, $runner)
    {
        $this->chainCollection($query, $runner);
        $conditions = $this->conditionsParser->parse($query->getWhereConditions());
        
        $options = array();
        
        if(($limit = $query->getLimit()) !== null) {
            $options['limit'] = $limit;
        }
        
        if(($offset = $query->getOffset()) !== null) {
            $options['skip'] = $offset;
        }
        
        if(count($fields = $query->getFields()) !== 0) {
            $options['projection'] = $this->fieldKeys($fields);
        }
        
        $order  = $query->getOrderBy();
        if (!empty($order)) {
            $ordering =  array();
            foreach ($order as $orderBy) {
                $ordering[$orderBy->field()] = $orderBy->direction() === 'asc' ? 1 : -1;
            }
            $options['sort'] = $ordering;
        }

        $runner->chainMethod('find', array($conditions, $options));
        return $runner;
    }

    protected function fieldKeys($fields)
    {
        return array_fill_keys($fields, 1);
    }

    protected function selectSingleQuery($query, $runner)
    {
        $this->chainCollection($query, $runner);
        $conditions = $this->conditionsParser->parse($query->getWhereConditions());
        $options = array();
        if(count($fields = $query->getFields()) !== 0) {
            $options['projection'] = $this->fieldKeys($fields);
        }
        $runner->chainMethod('findOne', array($conditions, $options));

        return $runner;
    }

    protected function insertQuery($query, $runner)
    {
        $this->chainCollection($query, $runner);

        if (($data = $query->getBatchData()) !== null) {
            $runner->chainMethod('insertMany', array($data));
        } elseif (($data = $query->getData()) !== null) {
            $runner->chainMethod('insertOne', array($data));
        }else
            throw new \PHPixie\Database\Exception\Parser("No data set for insertion");

        return $runner;
    }

    protected function updateQuery($query, $runner)
    {
        $this->chainCollection($query, $runner);

        $modifiers = array(
            '$set' => $query->getSet(),
            '$unset' => $this->fieldKeys($query->getUnset()),
            '$inc' => $query->getIncrement()
        );

        $data = array();
        foreach ($modifiers as $key=>$value) {
            if(!empty($value))
                $data[$key] = $value;
        }

        if (empty($data))
            throw new \PHPixie\Database\Exception\Parser("No modifiers specified for update");

        $conditions = $this->conditionsParser->parse($query->getWhereConditions());
        $runner->chainMethod('updateMany', array($conditions, $data));

        return $runner;
    }

    protected function deleteQuery($query, $runner)
    {
        $this->chainCollection($query, $runner);
        $conditions = $this->conditionsParser->parse($query->getWhereConditions());
        $runner->chainMethod('deleteMany', array($conditions));

        return $runner;
    }

    protected function countQuery($query, $runner)
    {
        $this->chainCollection($query, $runner);
        $conditions = $this->conditionsParser->parse($query->getWhereConditions());
        $runner->chainMethod('count', array($conditions));

        return $runner;
    }

    protected function chainCollection($query, $runner)
    {
        if (($collection = $query->getCollection()) !== null) {
            $runner->chainProperty($collection);
        }else
            throw new \PHPixie\Database\Exception\Parser("You must specify a collection for this query.");
    }
}
