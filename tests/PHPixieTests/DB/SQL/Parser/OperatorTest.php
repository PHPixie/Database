<?php
namespace PHPixieTests\DB\SQL\Parser;

/**
 * @coversDefaultClass \PHPixie\DB\SQL\Parser\Operator
 */
abstract class OperatorTest extends \PHPixieTests\DB\Parser\OperatorTest
{
    protected $db;
    protected $operatorParser;

    public function setUp()
    {
        $this->db = new \PHPixie\DB(null);
    }

    /**
     * @covers ::parse
     * @covers ::<protected>
     */
    public function testExceptions()
    {
        foreach ($this->exceptionConditions() as $condition) {
            $except = false;
            try {
                $this->operatorParser->parse($condition);
            } catch (\PHPixie\DB\Exception\Parser $e) {
                $except = true;
            }
            $this->assertEquals(true, $except);
        }
    }

    protected function exceptionConditions()
    {
        $conditions = array(
            $this->operator('a', '=', array(1, 2)),
            $this->operator('a', '=*', null),
            $this->operator('a', '=*', array(1,2)),
            $this->operator('a', 'like', array('hello',1)),
            $this->operator('a', 'regexp', array('hello',1)),
            $this->operator('a', 'in', array('one')),
            $this->operator('a', 'between', array(1)),
        );

        return $conditions;
    }


    
    protected function conditions()
    {
        $conditions = array(
            $this->operator('a', '=', array(1)),
            $this->operator('a', '=', array($this->db->expr('la'))),
            $this->operator('a', '=*', array($this->db->expr('la'))),
            $this->operator('a', '=*', array('b')),
            $this->operator('a', '=', array(null)),
            $this->operator('a', '!=', array(null)),
            $this->operator('a', '!=', array(1)),
            $this->operator('a', '!=*', array('c.b')),
            $this->operator('a', '>', array(1)),
            $this->operator('a', '>*', array('b')),
            $this->operator('a', 'like', array('hello')),
            $this->operator('a', 'regexp', array('hello')),
            $this->operator('a', 'in', array(array(1, 2))),
            $this->operator('a', 'in', array($this->db->expr('la'))),
            $this->operator('a', 'in', array($this->queryStub('fairy',array(1)))),
            $this->operator('a', 'between', array(1, 2)),
            $this->operator('a', 'not between', array(1, 2)),
            $this->operator('a.b', '=', array($this->db->expr('b', array(1)))),
            $this->operator($this->db->expr('a + b'), '=', array(1))
        );

        return $conditions;
    }

    protected function operator($field, $operator, $values)
    {
        return new \PHPixie\DB\Conditions\Condition\Operator($field, $operator, $values);
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

}
