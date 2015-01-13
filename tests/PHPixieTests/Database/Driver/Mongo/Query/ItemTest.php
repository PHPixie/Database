<?php
namespace PHPixieTests\Database\Driver\Mongo\Query;

/**
 * @coversDefaultClass \PHPixie\Database\Driver\Mongo\Query\Item
 */
abstract class ItemTest extends \PHPixieTests\Database\Driver\Mongo\QueryTest
{
     
    /**
     * @covers ::<public>
     * @covers ::<protected>
     */
    public function testWhereMethods()
    {
        $this->conditionMethodsTest('where');
        $this->subdocumentMethodsTest('where');
    }
    
    /**
     * @covers ::<public>
     * @covers ::<protected>
     */
    public function testShorthandMethods()
    {
        $this->conditionMethodsTest(null, false);
        $this->subdocumentMethodsTest();
    }
    
    /**
     * @covers ::addInOperatorCondition
     * @covers ::addWhereInOperatorCondition
     * @covers ::<protected>
     */
    public function testOperatorConditions()
    {
        $this->operatorConditionTest(
            'in',
            array('pixie', array(1)),
            array('where')
        );
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
            foreach(array('and', 'or', 'xor', '') as $logic) {
                foreach(array(false, true) as $negated) {
                    $method = 'start'.ucfirst($logic);
                    if($builderName !== null)
                        $method.=ucfirst($builderName);
                    if($negated)
                        $method.='Not';
                    $method.=ucfirst($type).'Group';
                    
                    $builderParams = array('pixie', $logic == '' ? 'and' : $logic, $negated);
                    if($builderName !== null)
                        $builderParams[]=$builderName;
                    
                    $this->builderMethodTest(
                        $method,
                        array('pixie'),
                        $this->query,
                        'test',
                        $builderParams,
                        'start'.ucfirst($type).'ConditionGroup'
                    );
                }
            }
            
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
