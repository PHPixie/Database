<?php
namespace PHPixieTests\DB\SQL\Parser;

/**
 * @coversDefaultClass \PHPixie\DB\SQL\Parser\Group
 */
abstract class GroupTest extends \PHPixieTests\DB\SQL\AbstractParserTest
{
    protected $db;
    protected $groupParser;
    protected $expected;

    public function setUp()
    {
        $this->db = new \PHPixie\DB(null);
    }

    protected function groups()
    {
        $groups = array(
            $this->builder()->_and('a',1)->getConditions(),
            $this->builder()
                        ->_and('a', 1)
                        ->_or('b', 1)
                        ->_xorNot('c', 1)
                        ->getConditions(),
            $this->builder()
                        ->_and('a', 1)
                        ->_or(function ($builder) {
                            $builder
                                ->_and('b', 1)
                                ->_or('c', 1);
                        })
                        ->_xorNot(function ($builder) {
                            $builder
                                ->_and('d', 1)
                                ->_and('e', 1);
                        })
                        ->getConditions(),
        );

        return $groups;
    }
    
    /**
     * @covers ::parse
     * @covers ::__construct
     * @covers ::<protected>
     */
    public function testParse()
    {
        foreach ($this->groups() as $key => $group) {
            $parsed = $this->groupParser->parse($group);
            $this->assertExpression($parsed, $this->expected[$key]);
        }
    }

    /**
     * @covers ::parse
     * @covers ::<protected>
     */
    public function testExceptions()
    {
        foreach ($this->exceptionGroups() as $group) {
            $except = false;
            try {
                $this->groupParser->parse($group);
            } catch (\PHPixie\DB\Exception\Parser $e) {
                $except = true;
            }
            $this->assertEquals(true, $except);
        }
    }

    protected function exceptionGroups()
    {
        $conditions = array(
            array($this->getMock('\PHPixie\DB\Conditions\Condition'))
        );

        return $conditions;
    }

    protected function builder()
    {
        return new \PHPixie\DB\Conditions\Builder($this->db->conditions());
    }

}
