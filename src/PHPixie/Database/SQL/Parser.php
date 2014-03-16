<?php

namespace PHPixie\Database\SQL;

abstract class Parser extends \PHPixie\Database\Parser
{
    protected $database;
    protected $driver;
    protected $config;
    protected $fragmentParser;
    protected $groupParser;

    protected $supportedJoins;

    public function __construct($database, $driver, $config, $fragmentParser, $groupParser)
    {
        parent::__construct($database, $driver, $config);
        $this->fragmentParser = $fragmentParser;
        $this->groupParser = $groupParser;
    }

    public function parse($query)
    {
        $this->query = $query;
        $expr = $this->database->expr();
        $type = $query->getType();

        switch ($type) {
            case 'select':
                $this->selectQuery($query, $expr);
                break;
            case 'insert':
                $this->insertQuery($query, $expr);
                break;
            case 'update':
                $this->updateQuery($query, $expr);
                break;
            case 'delete':
                $this->deleteQuery($query, $expr);
                break;
            case 'count':
                $this->countQuery($query, $expr);
                break;
            default:
                throw new \PHPixie\Database\Exception\Parser("Query type $type is not supported");
        }

        return $expr;
    }

    protected function selectQuery($query, $expr)
    {
        $expr->sql = "SELECT ";
        $this->appendFields($query, $expr);

        if ($this->query->getTable() !== null) {
            $expr->sql.= " FROM ";
            $this->appendTable($query, $expr);
        }

        $this->appendJoins($query, $expr);
        $this->appendConditions('where', $query->getWhereConditions(), $expr);
        $this->appendGroupBy($query, $expr);
        $this->appendConditions('having', $query->getHavingConditions(), $expr);
        $this->appendOrderBy($query, $expr);
        $this->appendLimitOffset($query, $expr);
        $this->appendUnion($query, $expr);
    }

    protected function insertQuery($query, $expr)
    {
        $expr->sql = "INSERT INTO ";

        $this->appendTable($query, $expr, true);
        $this->appendInsertValues($query, $expr);
    }

    protected function updateQuery($query, $expr)
    {
        $expr->sql = "UPDATE ";

        $this->appendTable($query, $expr, true);
        $this->appendJoins($query, $expr);
        $this->appendUpdateValues($query, $expr);
        $this->appendConditions('where', $query->getWhereConditions(), $expr);
        $this->appendOrderBy($query, $expr);
        $this->appendLimitOffset($query, $expr);

        return $expr;
    }

    protected function deleteQuery($query, $expr)
    {
        $expr->sql = "DELETE FROM ";

        $this->appendTable($query, $expr, true);
        $this->appendJoins($query, $expr);
        $this->appendConditions('where', $query->getWhereConditions(), $expr);
        $this->appendOrderBy($query, $expr);
        $this->appendLimitOffset($query, $expr);

        return $expr;
    }

    protected function countQuery($query, $expr)
    {
        $unions = $query->getUnions();
        $groupBy = $query->getGroupBy();

        if (!empty($unions) || !empty($groupBy))
            throw new \PHPixie\Database\Exception\Parser("COUNT queries don't support GROUP BY and UNION statements. Try using them in a subquery");

        $expr->sql .= "SELECT COUNT (1) AS ";
        $this->fragmentParser->appendColumn('count', $expr);
        $expr->sql .= " FROM ";
        $this->appendTable($query, $expr, true);
        $this->appendJoins($query, $expr);
        $this->appendConditions('where', $query->getWhereConditions(), $expr);
    }

    protected function appendTable($query, $expr, $required = false)
    {
        $table = $this->query->getTable();

        if ($required && $table === null) {
            $type = strtoupper($query->getType());
            throw new \PHPixie\Database\Exception\Parser("Table not specified for $type query");
        }

        $this->fragmentParser->appendTable($table['table'], $expr, $table['alias']);
    }

    protected function appendInsertValues($query, $expr)
    {
        if (($insertData = $query->getBatchData()) === null) {

            if (($data = $query->getData()) === null)
                $data = array();

            $insertData = array(
                'columns' => array_keys($data),
                'rows' => array(array_values($data))
            );
        }

        if (empty($insertData['columns']))
            return $this->appendEmptyInsertValues($expr);

        $expr->sql .= "(";

        foreach ($insertData['columns'] as $key => $column) {
            if($key > 0)
                $expr->sql.= ', ';
            $this->fragmentParser->appendColumn($column, $expr);
        }

        $expr->sql .= ") VALUES ";

        $columnsCount = count($insertData['columns']);

        foreach ($insertData['rows'] as $rowKey => $row) {

            if (count($row) != $columnsCount)
                    throw new \PHPixie\Database\Exception\Parser("The number of keys does not match the number of values for bulk insert.");

            if($rowKey > 0)
                $expr->sql.= ', ';

            $expr->sql.= '(';
            foreach ($row as $valueKey => $value) {
                if($valueKey > 0)
                    $expr->sql.= ', ';

                $this->fragmentParser->appendValue($value, $expr);
            }
            $expr->sql.= ')';
        }

    }

    protected function appendEmptyInsertValues($expr)
    {
        $expr->sql.= "() VALUES ()";
    }

    protected function appendUpdateValues($query, $expr)
    {
        $expr->sql .= " SET ";
        $data = $this->query->getData();

        if (empty($data))
            throw new \PHPixie\Database\Exception\Parser("Empty data passed to the UPDATE query");

        $first = true;
        foreach ($data as $column => $value) {
            if (!$first) {
                $expr->sql.= ', ';
            } else {
                $first = false;
            }
            $this->fragmentParser->appendColumn($column, $expr);
            $expr->sql.= " = ";
            $this->fragmentParser->appendValue($value, $expr);
        }
    }

    protected function appendJoins($query, $expr)
    {
        foreach ($query->getJoins() as $join) {

            if (!isset($this->supportedJoins[$join['type']]))
                throw new \PHPixie\Database\Exception\Parser("Join type '{$join['type']}' is not supported by this database driver");

            $expr->sql.= ' '.$this->supportedJoins[$join['type']]." JOIN ";
            $this->fragmentParser->appendTable($join['table'], $expr, $join['alias']);
            $this->appendConditions('on', $join['builder']->getConditions(), $expr);
        }
    }

    protected function appendConditions($prefix, $conditions, $expr)
    {
        if(empty($conditions))

            return;

        $expr->sql.= ' '.strtoupper($prefix).' ';
        $expr->append($this->groupParser->parse($conditions));
    }

    protected function appendGroupBy($query, $expr)
    {
        $groupBy = $query->getGroupBy();

        if (empty($groupBy))
            return;

        $expr->sql.= " GROUP BY ";
        foreach ($groupBy as $key => $column) {
            if ($key > 0)
                $expr->sql.= ', ';

            $this->fragmentParser->appendColumn($column, $expr);
        }
    }

    protected function appendOrderBy($query, $expr)
    {
        $orderBy = $query->getOrderBy();

        if (empty($orderBy))
            return;

        $expr->sql.= " ORDER BY ";
        foreach ($orderBy as $key => $order) {
            list($column, $dir) = $order;

            if ($key > 0)
                $expr->sql.= ', ';

            if ($dir !== 'asc' && $dir !== 'desc')
                throw new \PHPixie\Database\Exception\Parser("Order direction must be either 'asc' or  'desc'");

            $this->fragmentParser->appendColumn($column, $expr);
            $expr->sql.= ' '.strtoupper($dir);
        }
    }

    protected function appendLimitOffset($query, $expr)
    {
        $limit = $query->getLimit();
        $offset = $query->getOffset();

        if ($limit !== null)
            $expr->sql.= " LIMIT $limit";

        if ($offset !== null)
            $expr->sql.=" OFFSET $offset";
    }

    protected function appendUnion($query, $expr)
    {
        foreach ($query->getUnions() as $union) {
            list($subselect, $all) = $union;
            $expr->sql.= " UNION ";
            if ($all)
                $expr->sql.= "ALL ";

            if ($subselect instanceof \PHPixie\Database\SQL\Query) {
                $expr->append($subselect->parse());

            } elseif ($subselect instanceof \PHPixie\Database\SQL\Expression) {
                $expr->append($subselect);

            } else {
                throw new \PHPixie\Database\Exception\Parser("Union parameter must be either a Query object or SQL expression object");
            }
        }
    }

    protected function appendFields($query, $expr)
    {
        $fields = $query->getFields();

        if (empty($fields)) {
            $expr->sql.= '*';

            return;
        }

        $first = true;
        foreach ($query->getFields() as $key => $field) {
            if (!$first) {
                $expr->sql.= ', ';
            }else
                $first = false;

            if (!is_numeric($key)) {
                $this->fragmentParser->appendColumn($field, $expr);
                $expr->sql.=" AS ".$this->fragmentParser->quote($key);
            } else {
                $this->fragmentParser->appendColumn($field, $expr);
            }
        }
    }

}
