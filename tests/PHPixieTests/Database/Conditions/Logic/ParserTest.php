<?php

namespace PHPixieTests\Database\Conditions\Logic;

class ParserStub extends \PHPixie\Database\Conditions\Logic\Parser
{
    public $merges ;

    protected function normalize($c)
    {
        return $c;
    }

    protected function merge($l, $r)
    {
        $this->merges[] = $l->name.$r->name;
        $l->name .= $r->name;

        return $l;
    }

    public function parse($arr)
    {
        $this->merges = array();
        $this->parseLogic($arr);

        return $this->merges;
    }
}

class ConditionStub extends \PHPixie\Database\Conditions\Condition
{
    public $name;
}

/**
 * @coversDefaultClass \PHPixie\Database\Conditions\Logic\Parser
 */
class ParserTest extends \PHPixieTests\AbstractDatabaseTest
{
    protected $parser;

    public function setUp()
    {
        $this->parser = new ParserStub();
    }

    /**
     * @covers ::parseLogic
     * @covers ::parseLogicLevel
     */
    public function testParse()
    {
        $this->assertEquals(array(
            'ab',
            'abc'
        ), $this->parser->parse(array(
            $this->condition('a', 'and'),
            $this->condition('b', 'and'),
            $this->condition('c', 'and')
        )));

        $this->assertEquals(array(
            'bc',
            'abc'
        ), $this->parser->parse(array(
            $this->condition('a', 'and'),
            $this->condition('b', 'or'),
            $this->condition('c', 'and')
        )));

        $this->assertEquals(array(
            'bc',
            'de',
            'bcde',
            'abcde',
            'fg',
            'abcdefg'
        ), $this->parser->parse(array(
            $this->condition('a', 'and'),
            $this->condition('b', 'or'),
            $this->condition('c', 'and'),
            $this->condition('d', 'xor'),
            $this->condition('e', 'and'),
            $this->condition('f', 'or'),
            $this->condition('g', 'and')
        )));
    }

    protected function condition($name, $logic)
    {
        $cond = new ConditionStub();
        $cond->name = $name;
        $cond->setLogic($logic);

        return $cond;
    }

}
