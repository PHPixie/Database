<?php
namespace PHPixieTests\Database\Driver\Mongo\Query;

/**
 * @coversDefaultClass \PHPixie\Database\Driver\Mongo\Query\Item
 */
abstract class ItemTest extends \PHPixieTests\Database\Driver\Mongo\QueryTest
{
     
    /**
     * @covers ::<protected>
     * @covers ::getWhereBuilder
     * @covers ::getWhereConditions
     * @covers ::where
     * @covers ::orWhere
     * @covers ::xorWhere
     * @covers ::whereNot
     * @covers ::orWhereNot
     * @covers ::xorWhereNot
     * @covers ::startWhereGroup
     * @covers ::startOrWhereGroup
     * @covers ::startXorWhereGroup
     * @covers ::startWhereNotGroup
     * @covers ::startOrWhereNotGroup
     * @covers ::startXorWhereNotGroup
     * @covers ::endWhereGroup
     */
    public function testWhereMethods()
    {
        $this->conditionMethodsTest('where');
    }
    
    /**
     * @covers ::<protected>
     * @covers ::_and
     * @covers ::_or
     * @covers ::_xor
     * @covers ::_not
     * @covers ::andNot
     * @covers ::orNot
     * @covers ::xorNot
     * @covers ::startGroup
     * @covers ::startAndGroup
     * @covers ::startOrGroup
     * @covers ::startXorGroup
     * @covers ::startNotGroup
     * @covers ::startAndNotGroup
     * @covers ::startOrNotGroup
     * @covers ::startXorNotGroup
     * @covers ::endGroup
     */
    public function testShorthandMethods()
    {
        $this->conditionMethodsTest(null, false);
    }
    
    /**
     * @covers ::__call
     * @covers ::and
     * @covers ::or
     * @covers ::xor
     */
    public function testAliasedConditionMethods()
    {
        $this->conditionAliasTest();
    }
}
