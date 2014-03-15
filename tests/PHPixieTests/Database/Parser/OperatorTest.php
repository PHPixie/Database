<?php
namespace PHPixieTests\Database\Parser;

/**
 * @coversDefaultClass \PHPixie\Database\Parser\Operator
 */
abstract class OperatorTest extends \PHPixieTests\AbstractDatabaseTest {

    /**
     * @covers ::__construct
     * @covers ::parse
     * @covers ::<protected>
     */
    public function testParse()
    {
        foreach ($this->conditions() as $key => $condition) {
            $parsed = $this->operatorParser->parse($condition);
            $this->assertEquals($this->expected[$key][0], $parsed->sql);
            $this->assertEquals($this->expected[$key][1], $parsed->params);
        }
    }
    
    abstract protected function conditions();
}