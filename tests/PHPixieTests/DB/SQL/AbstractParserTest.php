<?php
namespace PHPixieTests\DB\SQL;

abstract class AbstractParserTest extends \PHPixieTests\AbstractDBTest
{
    protected function assertExpression($expr, $expected)
    {
        $this->assertEquals($expected[0], $expr->sql);
        $this->assertEquals($expected[1], $expr->params);
    }

    protected function queryStub($sql, $params = array())
    {
        $query = $this->getMock('\PHPixie\DB\Driver\PDO\Query', array('parse'), array(), '', false);
        $query
            ->expects($this->any())
            ->method('parse')
            ->will($this->returnValue($this->db->expr($sql, $params)));

        return $query;
    }

    protected function operator($field, $operator, $values)
    {
        return new \PHPixie\DB\Conditions\Condition\Operator($field, $operator, $values);
    }

}
