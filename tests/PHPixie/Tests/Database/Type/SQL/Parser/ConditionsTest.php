<?php
namespace PHPixie\Tests\Database\Type\SQL\Parser;

/**
 * @coversDefaultClass \PHPixie\Database\Type\SQL\Parser\Conditions
 */
abstract class ConditionsTest extends \PHPixie\Tests\Database\Type\SQL\AbstractParserTest
{
    protected $database;
    protected $conditionsParser;
    protected $expected;

    public function setUp()
    {
        $this->database = new \PHPixie\Database(null);
    }

    protected function groups()
    {

        $placeholderBuilder = $this->container()
                        ->_and('a', 1);

        $placeholder = $placeholderBuilder->addPlaceholder('or');
        $placeholderBuilder->xorNot(function ($container) {
                            $container
                                ->_and('d', 1)
                                ->_and('e', 1);
                        });
        $placeholder
                ->_and('b', 1)
                ->_or('c', 1);

        $groups = array(
            $this->container()->_and('a',1)->getConditions(),
            $this->container()
                        ->_and('a', 1)
                        ->_or('b', 1)
                        ->xorNot('c', 1)
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
            $parsed = $this->conditionsParser->parse($group);
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
                $this->conditionsParser->parse($group);
            } catch (\PHPixie\Database\Exception\Parser $e) {
                $except = true;
            }
            $this->assertEquals(true, $except);
        }
    }

    protected function exceptionGroups()
    {
        $conditions = array(
            array($this->quickMock('\PHPixie\Database\Conditions\Condition'))
        );

        return $conditions;
    }

    abstract protected function container();

}
