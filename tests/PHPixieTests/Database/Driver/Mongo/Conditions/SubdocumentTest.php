<?php

namespace PHPixieTests\Database\Driver\Mongo\Conditions;

/**
 * @coversDefaultClass \PHPixie\Database\Driver\Mongo\Conditions\Subdocument
 */
class SubdocumentTest extends \PHPixieTests\Database\Conditions\BuilderTest
{
    public function setUp()
    {
        $database = new \PHPixie\Database(null);
        $operatorParser = new \PHPixie\Database\Driver\Mongo\Parser\Operator();
        $groupParser = new \PHPixie\Database\Driver\Mongo\Parser\Group($database->driver('Mongo'), $operatorParser);
        $this->conditions = new \PHPixie\Database\Conditions;
        $this->builder = new \PHPixie\Database\Driver\Mongo\Conditions\Subdocument($this->conditions, $groupParser);
    }

    /**
     * @covers ::parse
     * @covers ::<protected>
     */
    public function testParse()
    {
        $this->builder
                    ->and('a', 1)
                    ->or('a', '>', 1);
        
        $this->assertEquals(array(
            '$or' => array(
                array( 'a' => 1 ),
                array(
                    'a' => array(
                        '$gt' => 1
                    )
                )
            )), $this->builder->parse());
    }

}
