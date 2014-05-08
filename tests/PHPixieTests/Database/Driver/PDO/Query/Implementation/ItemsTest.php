<?php

namespace PHPixieTests\Database\Driver\PDO\Query\Implementation;

/**
 * @coversDefaultClass \PHPixie\Database\Driver\PDO\Query\Implementation\Items
 */
class ItemsTest extends \PHPixieTests\Database\Driver\PDO\Query\ImplementationTest
{
    /**
     * @covers ::limit
     * @covers ::getLimit
     */
    public function testLimit()
    {
        $this->testBuilderMethod('limit', array(5), $this->query, 0); 
        $this->testBuilderMethod('getLimit', array(), 3, 3, 1); 
    }
    
    /**
     * @covers ::offset
     * @covers ::getOffset
     */
    public function testOffset()
    {
        $this->testBuilderMethod('offset', array(5), $this->query, 0); 
        $this->testBuilderMethod('getOffset', array(), 3, 1, 3);     }
    
    /**
     * @covers ::orderAscendingBy
     * @covers ::orderDescendingBy
     * @covers ::getOrderBy
     */
    public function testOrderBy()
    {
        $this->testBuilderMethod('orderAscendingBy', array('name'), $this->query, 0);
        $this->testBuilderMethod('orderDescendingBy', array('name'), $this->query, 1);
        $this->testBuilderMethod('getOrderBy', array(), array('test'), 2, array('test'));   
    }
    
    /**
     * @covers ::join
     * @covers ::getJoins
     */
    public function testJoin()
    {
        $this->testBuilderMethod('join', array('test', 't', 'left'), $this->query, 0);
        $this->testBuilderMethod('join', array('test'), $this->query, 1, array('test', null, 'inner'));
        $this->testBuilderMethod('getJoin', array(), array('test'), 2, array('test'));
    }
    

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
        $this->testConditionMethods('where');
    }
    
    /**
     * @covers ::<protected>
     * @covers ::_and
     * @covers ::_or
     * @covers ::_xor
     * @covers ::_andNot
     * @covers ::_orNot
     * @covers ::_xorNot
     * @covers ::startGroup
     * @covers ::startOrGroup
     * @covers ::startXorGroup
     * @covers ::startAndNotGroup
     * @covers ::startOrNotGroup
     * @covers ::startXorNotGroup
     * @covers ::endGroup
     */
    public function testShorthandMethods()
    {
        $this->testConditionMethods(null, false);
    }

    /**
     * @covers ::<protected>
     * @covers ::on
     * @covers ::orOn
     * @covers ::xorOn
     * @covers ::onNot
     * @covers ::orOnNot
     * @covers ::xorOnNot
     * @covers ::startOnGroup
     * @covers ::startOrOnGroup
     * @covers ::startXorOnGroup
     * @covers ::startOnNotGroup
     * @covers ::startOrOnNotGroup
     * @covers ::startXorOnNotGroup
     * @covers ::endOnGroup
     */
    public function testOnMethods()
    {
        $this->testConditionMethods('on', false, 'addCondition', 'startConditionGroup', 'endConditionGroup');
    }

    
    protected function testConditionMethods($name, $testConditionBuilder= true, $operatorMethod = 'addCondition', $startGroupMethod = 'startConditionGroup', $endGroupMethod = 'endConditionGroup')
    {
        $at = 0;
        foreach(array(false, true) as $negate) {
            foreach(array('and', 'or', 'xor') and $logic) {
                if($name !== null){
                    if($logic === 'and'){
                        $method = $name;
                    }else{
                        $method = $logic.ucfirst($name); 
                    }
                    if($negate)
                        $method.='Not';
                    $groupMethod = 'start'.ucfirst($method).'Group';
                }else{
                    $method = $logic;
                    if($negate)
                        $method.='Not';
                    
                    $groupMethod = 'start'.ucfirst($method).'Group';
                    $method = '_'.$method;
                }
                
                $this->testBuilderMethod($method, array('test', 1, 2), $this->query, $at++, null, array(array('test', 1, 2), $logic, $negate, $name), $operatorMethod);
                
                $this->testBuilderMethod($groupMethod, array(), $this->query, $at++, null, array($logic, $negate, $name), $startGroupMethod);
                
            }
        }
        
        $this->testBuilderMethod('end'.ucfirst($name).'Group', array(), $this->query, $at++, null, array($name), $endGroupMethod);
        
        if($testConditionBuilder){
            $this->testBuilderMethod('get'.ucfirst($name).'Builder', array(), $this->query, $at++, null, array($name), 'conditionBuilder');
        $this->testBuilderMethod('get'.ucfirst($name).'Conditions', array(), $this->query, $at++, null, array($name), 'getConditions');
        }
        
    }
}