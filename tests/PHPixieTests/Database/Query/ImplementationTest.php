<?php
namespace PHPixieTests\Database\Query;

/**
 * @coversDefaultClass \PHPixie\Database\Query\Implementation
 */
abstract class ImplementationTest extends \PHPixieTests\AbstractDatabaseTest
{
    protected $connection;
    protected $parser;
    protected $builder;
    protected $query;
    protected $type;
    
    protected function setUp()
    {
        $this->connection = $this->getConnection();
        $this->parser = $this->getParser();
        $this->builder = $this->getBuilder();
        $this->query = $this->query();
    }

    /**
     * @covers ::__construct
     * @covers \PHPixie\Database\Query\Implementation::__construct
     */
    public function testConstruct()
    {
        
    }
    
    /**
     * @covers ::type
     */
    public function testType()
    {
        $this->assertEquals($this->type, $this->query->type());
    }

 
    protected function parserTest()
    {
        $query = $this->query();
        $this->parser
                ->expects($this->any())
                ->method('parse')
                ->with ($query)
                ->will($this->returnValue('a'));
        $this->assertEquals('a', $query->parse());
    }

    protected function builderMethodTest($method, $with, $will = null, $builderWill = null, $builderWith = null, $builderMethod = null)
    {
        if($builderWith === null)
            $builderWith = $with;

        if($builderMethod === null)
            $builderMethod = $method;
        
        if($builderMethod === null)
            $builderMethod = $method;
        
        $methodMock = $this->builder;
        
        $methodMock = $methodMock->expects($this->at(0));

        $methodMock = $methodMock->method($builderMethod);
        
        $methodMock = call_user_func_array(array($methodMock, 'with'), $builderWith);
        $methodMock->will($this->returnValue($builderWill));
        
        $result = call_user_func_array(array($this->query, $method), $with);
        $this->assertEquals($will, $result);
    }
    
    /**
     * @covers ::__call
     */
    public function testMethodException()
    {
        $this->setExpectedException('\PHPixie\Database\Exception\Builder');
        $this->query->test();
    }
    
    protected function conditionMethodsTest($name, $testConditionBuilder= true, $operatorMethod = 'addCondition', $startGroupMethod = 'startConditionGroup', $endGroupMethod = 'endConditionGroup', $passBuilderName = true)
    {
        foreach(array(false, true) as $negate) {
            foreach(array('and', 'or', 'xor', 'short_and') as $logic) {
                
                if($name !== null){
                    if($logic === 'short_and'){
                        $method = $name;
                    }else{
                        $method = $logic.ucfirst($name); 
                    }
                    if($negate)
                        $method.='Not';
                    $groupMethod = 'start'.ucfirst($method).'Group';
                }else{
                    if($logic === 'short_and'){
                        $method = $negate ? 'not' : '';
                    }else{
                        $method = $logic;
                        if($negate)
                            $method.='Not';
                    }
                    
                    $groupMethod = ($logic === 'short_and' ? '' : $logic).($negate?'Not':'');
                    $groupMethod = 'start'.ucfirst($groupMethod).'Group';
                    
                    if(!$negate || ($negate && $logic === 'short_hand'))
                        $method = '_'.$method;
                }
                
                if($logic === 'short_and')
                    $logic = 'and';
                
                if($method !== '_'){
                    $with = array(array('test', 1, 2), $logic, $negate);
                    if($passBuilderName)
                        $with[]=$name;
                    $this->builderMethodTest($method, array('test', 1, 2), $this->query,  null, $with, $operatorMethod);
                }
                
                $with = array($logic, $negate);
                if($passBuilderName)
                    $with[]=$name;
                $this->builderMethodTest($groupMethod, array(), $this->query, null, $with, $startGroupMethod);
                
            }
        }
        
        $with = $passBuilderName ? array($name) : array();
        $this->builderMethodTest('end'.ucfirst($name).'Group', array(), $this->query, null, $with, $endGroupMethod);
        
        if($testConditionBuilder){
            $this->builderMethodTest('get'.ucfirst($name).'Builder', array(), $this->builder, $this->builder, $with, 'conditionBuilder');
            $this->builderMethodTest('get'.ucfirst($name).'Conditions', array(), array('test'), array('test'), $with, 'getConditions');
        }
        
    }
    
    protected function conditionAliasTest()
    {
        $this->builderMethodTest('and', array('test', 1, 2), $this->query,  null, array(array('test', 1, 2), 'and', false), 'addCondition');
        $this->builderMethodTest('or', array('test', 1, 2), $this->query,  null, array(array('test', 1, 2), 'or', false), 'addCondition');
        $this->builderMethodTest('xor', array('test', 1, 2), $this->query,  null, array(array('test', 1, 2), 'xor', false), 'addCondition');
        $this->builderMethodTest('not', array('test', 1, 2), $this->query,  null, array(array('test', 1, 2), 'and', true), 'addCondition');
    }

    protected function setClearGetTest($name, $paramSets, $type = 'value', $clearGetName = null)
    {
        $uname = ucfirst($name);
        
        if($clearGetName === null)
            $clearGetName = $name;
        
        foreach($paramSets as $paramSet)
        {
            $with = $paramSet[0];
            if(isset($paramSet[1])){
                $builderWith = $paramSet[1];
            }else{
                $builderWith = $with;
            }
            $builderMethod = $type === 'value' ? 'set'.$uname : 'add'.$uname;
            $this->builderMethodTest($name, $with, $this->query, null, $builderWith, $builderMethod);    
        }
        
        $this->clearGetTest($clearGetName, $type);
    }
    
    protected function clearGetTest($name, $type = 'value')
    {
        $uname = ucfirst($name);
        $utype = ucfirst($type);
        
        $this->builderMethodTest('clear'.$uname, array(), $this->query, null, array($name), 'clear'.$utype);
        $this->builderMethodTest('get'.$uname, array(), 5, 5, array($name), 'get'.$utype);
    }
    
    abstract public function testExecute();
    abstract protected function getConnection();
    abstract protected function getParser();
    abstract protected function getBuilder();
    abstract protected function query();
}
