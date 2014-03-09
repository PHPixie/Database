<?php

namespace PHPixie\DB\SQL;

abstract class Parser extends \PHPixie\DB\Parser
{
    protected $db;
    protected $driver;
    protected $config;
    protected $fragmentParser;
    protected $groupParser;

    protected $supportedJoins;

    public function __construct($db, $driver, $config, $fragmentParser, $groupParser)
    {
        parent::__construct($db, $driver, $config);
        $this->fragmentParser = $fragmentParser;
        $this->groupParser = $groupParser;
    }

    public function parse($query)
    {
        $this->query = $query;
        $expr = $this->db->expr();
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
                throw new \PHPixie\DB\Exception\Parser("Query type $type is not supported");
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
        $this->appendConditions('where', $query->getConditions('where'), $expr);
        $this->appendGroupBy($query, $expr);
        $this->appendConditions('having', $query->getConditions('having'), $expr);
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
        $this->appendConditions('where', $query->getConditions('where'), $expr);
        $this->appendOrderBy($query, $expr);
        $this->appendLimitOffset($query, $expr);

        return $expr;
    }

    protected function deleteQuery($query, $expr)
    {
        $expr->sql = "DELETE FROM ";

        $this->appendTable($query, $expr, true);
        $this->appendJoins($query, $expr);
        $this->appendConditions('where', $query->getConditions('where'), $expr);
        $this->appendOrderBy($query, $expr);
        $this->appendLimitOffset($query, $expr);

        return $expr;
    }

    protected function countQuery($query, $expr)
    {
        $unions = $query->getUnions();
        $groupBy = $query->getGroupBy();

        if (!empty($unions) || !empty($groupBy))
            throw new \PHPixie\DB\Exception\Parser("COUNT queries don't support GROUP BY and UNION statements. Try using them in a subquery");

        $expr->sql .= "SELECT COUNT (1) AS ";
        $this->fragmentParser->appendColumn('count', $expr);
        $expr->sql .= " FROM ";
        $this->appendTable($query, $expr, true);
        $this->appendJoins($query, $expr);
        $this->appendConditions('where', $query->getConditions('where'), $expr);
    }

    protected function appendTable($query, $expr, $required = false)
    {
        $table = $this->query->getTable();

        if ($required && $table === null) {
            $type = strtoupper($query->getType());
            throw new \PHPixie\DB\Exception\Parser("Table not specified for $type query");
        }

        $this->fragmentParser->appendTable($table['table'], $expr, $table['alias']);
    }

    protected function appendInsertValues($query, $expr)
    {
        $data = $query->getData();

        if (empty($data))
            return $this->appendEmptyInsertValues($query, $expr);

        $columnsExpr = $this->db->expr();
        $valuesExpr  = $this->db->expr();

        $first = true;
        foreach ($data as $column => $value) {
            if (!$first) {
                $columnsExpr->sql.= ', ';
                $valuesExpr->sql.= ', ';
            } else {
                $first = false;
            }
            $this->fragmentParser->appendColumn($column, $columnsExpr);
            $this->fragmentParser->appendValue($value, $valuesExpr);
        }
        $expr->sql .= "(";
        $expr->append($columnsExpr);
        $expr->sql .= ") VALUES (";
        $expr->append($valuesExpr);
        $expr->sql .= ")";
    }

    protected function appendEmptyInsertValues($query, $expr)
    {
        $expr->sql.= "() VALUES()";
    }

    protected function appendUpdateValues($query, $expr)
    {
        $expr->sql .= " SET ";
        $data = $this->query->getData();

        if (empty($data))
            throw new \PHPixie\DB\Exception\Parser("Empty data passed to the UPDATE query");

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
                throw new \PHPixie\DB\Exception\Parser("Join type '{$join['type']}' is not supported by this database driver");

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

    public function appendOrderBy($query, $expr)
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
                throw new \PHPixie\DB\Exception\Parser("Order direction must be either 'asc' or  'desc'");

            $this->fragmentParser->appendColumn($column, $expr);
            $expr->sql.= ' '.strtoupper($dir);
        }
    }

    public function appendLimitOffset($query, $expr)
    {
        $limit = $query->getLimit();
        $offset = $query->getOffset();

        if ($limit !== null)
            $expr->sql.= " LIMIT $limit";

        if ($offset !== null)
            $expr->sql.=" OFFSET $offset";
    }

    public function appendUnion($query, $expr)
    {
        foreach ($query->getUnions() as $union) {
            list($subselect, $all) = $union;
            $expr->sql.= " UNION ";
            if ($all)
                $expr->sql.= "ALL ";

            if ($subselect instanceof \PHPixie\DB\SQL\Query) {
                $expr->append($subselect->parse());

            } elseif ($subselect instanceof \PHPixie\DB\SQL\Expression) {
                $expr->append($subselect);

            } else {
                throw new \PHPixie\DB\Exception\Parser("Union parameter must be either a Query object or SQL expression object");
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
        foreach ($query->getFields() as $field) {
            if ($first) {
                $expr->sql.= ', ';
                $first = false;
            }
            if (is_array($field)) {
                $this->fragmentParser->appendColumn($field[0], $expr);
                $expr->sql.=" AS ".$this->fragmentParser->quote($field[1]);
            } else {
                $this->fragmentParser->appendColumn($field, $expr);
            }
        }
    }

}
