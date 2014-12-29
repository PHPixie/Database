<?php
namespace PHPixieTests\Database\Driver\Mongo\Query;

/**
 * @coversDefaultClass \PHPixie\Database\Driver\Mongo\Query\Item
 */
abstract class ItemTest extends \PHPixieTests\Database\Driver\Mongo\QueryTest
{
     
    /**
     * @covers ::<protected>
     * @covers ::addWhereCondition
     * @covers ::getWhereContainer
     * @covers ::getWhereConditions
     * @covers ::where
     * @covers ::andWhere
     * @covers ::orWhere
     * @covers ::xorWhere
     * @covers ::whereNot
     * @covers ::andWhereNot
     * @covers ::orWhereNot
     * @covers ::xorWhereNot
     * @covers ::startWhereGroup
     * @covers ::startAndWhereGroup
     * @covers ::startOrWhereGroup
     * @covers ::startXorWhereGroup
     * @covers ::startWhereNotGroup
     * @covers ::startAndWhereNotGroup
     * @covers ::startOrWhereNotGroup
     * @covers ::startXorWhereNotGroup
     * @covers ::addWhereOperatorCondition
     * @covers ::startWhereConditionGroup
     * @covers ::addWherePlaceholder
     * @covers ::addWhereSubdocumentCondition
     * @covers ::addWhereSubarrayItemCondition
     * @covers ::endWhereGroup
     */
    public function testWhereMethods()
    {
        $this->conditionMethodsTest('where');
        $this->subdocumentMethodsTest('where');
    }
    
    /**
     * @covers ::<protected>
     * @covers ::addCondition
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
     * @covers ::addOperatorCondition
     * @covers ::startConditionGroup
     * @covers ::addPlaceholder
     * @covers ::addSubdocumentCondition
     * @covers ::addSubarrayItemCondition
     */
    public function testShorthandMethods()
    {
        $this->conditionMethodsTest(null, false);
        $this->subdocumentMethodsTest();
    }
    
    /**
     * @covers ::__call
     */
    public function testAliasedConditionMethods()
    {
        $this->conditionAliasTest();
    }
    
    protected function subdocumentMethodsTest($builderName = null)
    {
        $types = array('subdocument', 'subarrayItem');
        
        foreach($types as $type) {
            $container = $this->quickMock('\PHPixie\Database\Conditions\Builder\Container', array());
            $params = array('pixie', 'or', true, false);
            $builderParams = $params;
            if($builderName !== null)
                $builderParams[]=$builderName;
        
            $this->builderMethodTest(
                'add'.ucfirst($builderName).ucfirst($type).'Placeholder',
                $params,
                $container,
                $container,
                $builderParams,
                 'add'.ucfirst($type).'Placeholder'
            );
        }
    }
}
