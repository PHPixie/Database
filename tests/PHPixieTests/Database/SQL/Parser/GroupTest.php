<?php
namespace PHPixieTests\Database\SQL\Parser;

/**
 * @coversDefaultClass \PHPixie\Database\SQL\Parser\Group
 */
abstract class GroupTest extends \PHPixieTests\Database\SQL\AbstractParserTest
{
    protected $database;
    protected $groupParser;
    protected $expected;

    public function setUp()
    {
        $this->database = new \PHPixie\Database(null);
    }

    protected function groups()
    {
     
        $placeholderBuilder = $this->builder()
                        ->_and('a', 1);
        
        $placeholder = $placeholderBuilder->addPlaceholder('or')->builder();
        $placeholderBuilder->_xorNot(function ($builder) {
                            $builder
                                ->_and('d', 1)
                                ->_and('e', 1);
                        });
        $placeholder
                ->_and('b', 1)
                ->_or('c', 1);
        
        $groups = array(
            $this->builder()->_and('a',1)->getConditions(),
            $this->builder()
                        ->_and('a', 1)
                        ->_or('b', 1)
                        ->_xorNot('c', 1)
                        ->getConditions(),
                        $placeholderBuilder->getConditions(),
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
            } catch (\PHPixie\Database\Exception\Parser $e) {
                $except = true;
            }
            $this->assertEquals(true, $except);
        }
    }

    protected function exceptionGroups()
    {
        $conditions = array(
            array($this->getMock('\PHPixie\Database\Conditions\Condition'))
        );

        return $conditions;
    }

    protected function builder()
    {
        return new \PHPixie\Database\Conditions\Builder($this->database->conditions());
    }

}
