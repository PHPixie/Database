<?php

namespace PHPixieTests\DB\Conditions\Logic;

class ParserStub extends \PHPixie\DB\Conditions\Logic\Parser
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
        $this->expandGroup($arr);

        return $this->merges;
    }
}

/**
 * @coversDefaultClass \PHPixie\DB\Conditions\Logic\Parser
 */
class ParserTest extends \PHPUnit_Framework_TestCase
{
    protected $parser;

    public function setUp()
    {
        $this->parser = new ParserStub();
    }

    /**
     * @covers ::expandGroup
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
        $cond = new \stdClass;
        $cond->name = $name;
        $cond->logic = $logic;

        return $cond;
    }

}
