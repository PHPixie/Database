<?php

namespace PHPixieTests\DB\Driver\Mongo\Conditions\Condition;

/**
 * @coversDefaultClass \PHPixie\DB\Driver\Mongo\Conditions\Condition\Expanded
 */
class ExpandedTest extends \PHPixieTests\AbstractDBTest
{
    protected $expanded;

    protected function setUp()
    {
        $this->expanded = $this->getExpanded();
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
        $exp1 = $this->getExpanded($a);
        $exp1->add($b);
        $exp2 = $this->getExpanded();
        $exp2->add($c)->add($b);
        $this->expanded->add($exp1)->add($exp2, 'or');

        $this->expanded->negate();
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

        $this->expanded->add($a);
        $this->assertGroup(array(array('a')));

        $this->expanded->add($b);
        $this->assertGroup(array(array('a', 'b')));

        $this->expanded->add($c);
        $this->assertGroup(array(array('a', 'b', 'c')));

        $this->expanded->add($d,'or');
        $this->assertGroup(array(array('a', 'b', 'c'), array('d')));

        $this->expanded->add($e);
        $this->assertGroup(array(array('a', 'b', 'c', 'e'), array('d', 'e')));

        $exp = $this->getExpanded();
        $f = $this->getOperator('f');
        $g = $this->getOperator('g');
        $h = $this->getOperator('h');
        $exp->add($f, 'or');
        $this->assertGroup(array(array('f')),$exp);
        $exp->add($g);
        $exp->add($h, 'or');

        $this->expanded->add($exp);

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

        $this->expanded->add($a);
        $this->expanded->add($b);
        $this->expanded->add($c,'or');
        $this->expanded->negate();
        $this->assertGroup(array(array('!a','!c'),array('!b','!c')));

        $exp = $this->getExpanded();
        $f = $this->getOperator('f');
        $g = $this->getOperator('g');
        $h = $this->getOperator('h');
        $exp->add($f, 'or');
        $exp->add($g);
        $exp->add($h, 'or');

        $exp2 = $this->getExpanded();
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

        $exp->negate();
        $this->assertGroup(array(
            array('!f', '!h','!i','!k'),
            array('!f', '!h', '!j', '!k'),
            array('!g', '!h','!i','!k'),
            array('!g', '!h', '!j', '!k')
        ), $exp);

        $l = $this->getOperator('l');
        $exp->add($l);

        $exp->negate(true);
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
                $row[] = ($op->negated()?'!':'').$op->field;
            }
            $parsed[] = $row;
        }

        return $parsed;
    }
    
    public function testClone() {
        $exp1 = $this->getExpanded($this->getOperator('a'));
        $exp2 = clone $exp1;
    }
    
    /**
     * @covers ::add
     */
    public function testAddOperatorException() {
        $this->setExpectedException('\PHPixie\DB\Exception\Parser');
        $this->expanded->add(array());
    }

    /**
     * @covers ::add
     */
    public function testAddLogicException() {
        $this->setExpectedException('\PHPixie\DB\Exception\Parser');
        $this->expanded->add($this->getOperator('a'));
        $this->expanded->add($this->getOperator('b'), 'xor');
    }

    protected function assertGroup($expected, $expanded = null)
    {
        if ($expanded == null)
            $expanded = $this->expanded;

        $parsed = $this->parseGroups($expanded->groups());
        //print_r($parsed);
        $this->assertEquals($expected, $parsed);
    }

    protected function getOperator($field)
    {
        return $mock = $this->getMockBuilder('\PHPixie\DB\Conditions\Condition\Operator')
                        ->setMethods(null)
                        ->setConstructorArgs(array($field, '=', strtoupper($field)))
                        ->getMock();
    }

    protected function getExpanded($operator = null)
    {
        return new \PHPixie\DB\Driver\Mongo\Conditions\Condition\Expanded($operator);
    }

}
