<?php

namespace PHPixie\DB\Driver\Mongo;

class Parser extends \PHPixie\DB\Parser
{
    protected $groupParser;

    public function __construct($db, $driver, $config, $groupParser)
    {
        parent::__construct($db, $driver, $config);
        $this->groupParser = $groupParser;
    }

    public function parse($query)
    {
        $runner = $this->driver->runner();;
        switch ($query->getType()) {
            case 'select':
                return $this->selectQuery($query, $runner);
            case 'insert':
                return $this->insertQuery($query, $runner);
            case 'update':
                return $this->updateQuery($query, $runner);
            case 'delete':
                return $this->deleteQuery($query, $runner);
            case 'count':
                return $this->countQuery($query, $runner);
            default:
                throw new \Exception("Query type $type is not supported");
        }
    }

    public function selectQuery($query, $runner)
    {
        $this->chainCollection($query, $runner);
        $fields = $query->getFields();
        $conditions = $this->groupParser->parse($query->getConditions('where'));
        $limit = $query->getLimit();
        $offset = $query->getOffset();
        $orderBy = $query->getOrderBy();

        if (empty($offset) && $limit === 1 && empty($orderBy)) {
            $runner->chainMethod('findOne', array($conditions, $fields));
        } else {
            $runner->chainMethod('find', array($conditions, $fields));

            if (!empty($orderBy)) {
                $ordering =  array();
                foreach ($orderBy as $order) {
                    list($column, $dir) = $order;
                    $ordering[$column] = $dir == 'asc' ? 1 : -1;
                }
                $runner->chainMethod('sort', array($ordering));
            }

            if ($limit !== null)
                $runner->chainMethod('limit', array($limit));

            if ($offset !== null)
                $runner->chainMethod('skip', array($offset));
        }

        return $runner;
    }

    public function insertQuery($query, $runner)
    {
        $this->chainCollection($query, $runner);
        $data = $query->getData();
        if ($data === null)
            throw new \PHPixie\DB\Exception\Parser("No data set for insertion");

        $runner->chainMethod('insert', array($data));

        return $runner;
    }

    public function updateQuery($query, $runner)
    {
        $this->chainCollection($query, $runner);
        $data = $query->getData();
        if ($data === null)
            throw new \PHPixie\DB\Exception\Parser("No data set for update");

        $conditions = $this->groupParser->parse($query->getConditions('where'));
        $runner->chainMethod('update', array($conditions, $data, array('multiple' => true)));

        return $runner;
    }

    public function deleteQuery($query, $runner)
    {
        $this->chainCollection($query, $runner);
        $conditions = $this->groupParser->parse($query->getConditions('where'));
        $runner->chainMethod('remove', array($conditions));

        return $runner;
    }

    public function countQuery($query, $runner)
    {
        $this->chainCollection($query, $runner);
        $conditions = $this->groupParser->parse($query->getConditions('where'));
        if (!empty($conditions))
            $runner->chainMethod('find', array($conditions));
        $runner->chainMethod('count');

        return $runner;
    }

    protected function chainCollection($query, $runner)
    {
        if (($collection = $query->getCollection()) !== null) {
            $runner->chainProperty($collection);
        }else
            throw new \PHPixie\DB\Exception\Parser("You must specify a collection for this query.");
    }
}
