<?php

namespace PHPixieTests\Database\Driver\Mongo\Parser\Conditions;

/**
 * @coversDefaultClass \PHPixie\Database\Driver\Mongo\Parser\Conditions\ExpandedGroup
 */
class ExpandedGroupTest extends \PHPixieTests\Database\Conditions\Condition\ImplementationTest
{
    /**
     * @covers ::negate
     * @covers ::isNegated
     * @covers ::setIsNegated
     */
    public function testNegation()
    {
        $this->assertEquals(false, $this->condition->isNegated());
        $this->assertEquals($this->condition, $this->condition->negate());
        $this->assertEquals(false, $this->condition->isNegated());
        
        $this->assertEquals($this->condition, $this->condition->setIsNegated(true));
        $this->assertEquals(false, $this->condition->isNegated());
        $this->assertEquals($this->condition, $this->condition->setIsNegated(false));
        $this->assertEquals(false, $this->condition->isNegated());
    }
    
    /**
     * @covers ::__construct
     * @covers ::add
     * @covers ::negate
     * @covers ::groups
     * @covers ::<protected>
     * @covers ::__clone
     */
    public function testSimpleOptimization()
    {
        $a = $this->getOperator('a');
        $b = $this->getOperator('b');
        $c = $this->getOperator('c');
        $exp1 = $this->getExpandedGroup($a);
        $exp1->add($b);
        $exp2 = $this->getExpandedGroup();
        $exp2->add($c)->add($b);
        $this->condition->add($exp1)->add($exp2, 'or');

        $this->condition->negate();
        $this->assertGroup(array(array('!a','!c'),array('!b')));
    }

    /**
     * @covers ::add
     * @covers ::negate
     * @covers ::groups
     * @covers ::<protected>
     * @covers ::__clone
     */
    public function testAdd()
    {
        $a = $this->getOperator('a');
        $b = $this->getOperator('b');
        $c = $this->getOperator('c');
        $d = $this->getOperator('d');
        $e = $this->getOperator('e');

        $this->condition->add($a);
        $this->assertGroup(array(array('a')));

        $this->condition->add($b);
        $this->assertGroup(array(array('a', 'b')));

        $this->condition->add($c);
        $this->assertGroup(array(array('a', 'b', 'c')));

        $this->condition->add($d,'or');
        $this->assertGroup(array(array('a', 'b', 'c'), array('d')));

        $this->condition->add($e);
        $this->assertGroup(array(array('a', 'b', 'c', 'e'), array('d', 'e')));

        $exp = $this->getExpandedGroup();
        $f = $this->getOperator('f');
        $g = $this->getOperator('g');
        $h = $this->getOperator('h');
        $exp->add($f, 'or');
        $this->assertGroup(array(array('f')),$exp);
        $exp->add($g);
        $exp->add($h, 'or');

        $this->condition->add($exp);

        $this->assertGroup(array(
            array('a', 'b', 'c', 'e', 'f', 'g'),
            array('a', 'b', 'c', 'e', 'h'),
            array('d', 'e', 'f', 'g'),
            array('d', 'e', 'h')
        ));
    }

    /**
     * @covers ::add
     * @covers ::negate
     * @covers ::setIsNegated
     * @covers ::groups
     * @covers ::<protected>
     * @covers ::__clone
     */
    public function testNegate()
    {
        $a = $this->getOperator('a');
        $b = $this->getOperator('b');
        $c = $this->getOperator('c');
        $d = $this->getOperator('d');
        $e = $this->getOperator('e');

        $this->condition->add($a);
        $this->condition->add($b);
        $this->condition->add($c,'or');
        $this->condition->negate();
        $this->assertGroup(array(array('!a','!c'),array('!b','!c')));

        $exp = $this->getExpandedGroup();
        $f = $this->getOperator('f');
        $g = $this->getOperator('g');
        $h = $this->getOperator('h');
        $exp->add($f, 'or');
        $exp->add($g);
        $exp->add($h, 'or');

        $exp2 = $this->getExpandedGroup();
        $i = $this->getOperator('i');
        $j = $this->getOperator('j');
        $k = $this->getOperator('k');
        $exp2->add($i, 'or');
        $exp2->add($j);
        $exp2->add($k, 'or');

        $exp->add($exp2, 'or');
        $this->assertGroup(array(
            array('f', 'g'),
            array('h'),
            array('i', 'j'),
            array('k'),
        ), $exp);

        $exp->setIsNegated(true);
        $this->assertGroup(array(
            array('!f', '!h','!i','!k'),
            array('!f', '!h', '!j', '!k'),
            array('!g', '!h','!i','!k'),
            array('!g', '!h', '!j', '!k')
        ), $exp);

        $l = $this->getOperator('l');
        $exp->add($l);

        $exp->negate();
        $this->assertGroup(array(
            array('f', 'g'),
            array('h'),
            array('i', 'j'),
            array('k'),
            array('!l')
        ), $exp);
    }

    protected function parseGroups($groups)
    {
        $parsed = array();
        foreach ($groups as $group) {
            $row = array();
            foreach ($group as $op) {
                $row[] = ($op->isNegated()?'!':'').$op->field();
            }
            $parsed[] = $row;
        }

        return $parsed;
    }


    /**
     * @covers ::__clone
     */
    public function testClone()
    {
        $exp1 = $this->getExpandedGroup($this->getOperator('a'));
        $exp2 = clone $exp1;
    }
    
    /**
     * @covers ::operatorConditions
     */
    public function testOperatorConditions()
    {
        $a = $this->getOperator('a');
        $b = $this->getOperator('b');
        
        $this->condition->add($a);
        $this->condition->add($b, 'or');;
        $this->condition->negate();
        
        $conditions = $this->condition->operatorConditions();
        $this->assertSame(2, count($conditions));
        $this->assertSame('a', $conditions[0]->field());
        $this->assertSame('b', $conditions[1]->field());
    }

    /**
     * @covers ::add
     */
    public function testAddOperatorException()
    {
        $this->setExpectedException('\PHPixie\Database\Exception\Parser');
        $this->condition->add(array());
    }

    /**
     * @covers ::add
     */
    public function testAddLogicException()
    {
        $this->setExpectedException('\PHPixie\Database\Exception\Parser');
        $this->condition->add($this->getOperator('a'));
        $this->condition->add($this->getOperator('b'), 'xor');
    }

    protected function assertGroup($expected, $expanded = null)
    {
        if ($expanded == null)
            $expanded = $this->condition;

        $parsed = $this->parseGroups($expanded->groups());
        $this->assertEquals($expected, $parsed);
    }

    protected function getOperator($field)
    {
        return $mock = $this->getMockBuilder('\PHPixie\Database\Conditions\Condition\Field\Operator')
                        ->setMethods(null)
                        ->setConstructorArgs(array($field, '=', strtoupper($field)))
                        ->getMock();
    }

    protected function getExpandedGroup($operator = null)
    {
        return new \PHPixie\Database\Driver\Mongo\Parser\Conditions\ExpandedGroup($operator);
    }
    
    protected function condition()
    {
        return $this->getExpandedGroup();
    }

}
