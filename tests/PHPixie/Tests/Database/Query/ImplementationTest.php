<?php
namespace PHPixie\Tests\Database\Query;

/**
 * @coversDefaultClass \PHPixie\Database\Query\Implementation
 */
abstract class ImplementationTest extends \PHPixie\Tests\AbstractDatabaseTest
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
    
    /**
     * @covers ::connection
     */
    public function testConnection()
    {
        $this->assertEquals($this->connection, $this->query->connection());
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
        if($builderWill === null)
            $builderWill = $will;
        
        if($builderWith === null)
            $builderWith = $with;

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
    
    protected function conditionMethodsTest($name, $testContainer = true, $methodOverrides = array(), $passBuilderName = true)
    {
        $methods = array(
            'buildCondition',
            'addCondition',
            'startConditionGroup',
            'endConditionGroup',
            'addOperatorCondition',
            'addPlaceholder'
        );
        
        $methodMap = array();
        foreach($methods as $method) {
            $methodName = $method;
            if(array_key_exists($method, $methodOverrides)) {
                $methodName = $methodOverrides[$method];
            }
            $methodMap[$method] = $methodName;
        }
        
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
                    $with = array($logic, $negate, array('test', 1, 2));
                    if($passBuilderName)
                        $with[]=$name;
                    $this->builderMethodTest($method, array('test', 1, 2), $this->query,  null, $with, $methodMap['buildCondition']);
                }
                
                $with = array($logic, $negate);
                if($passBuilderName)
                    $with[]=$name;
                $this->builderMethodTest($groupMethod, array(), $this->query, null, $with, $methodMap['startConditionGroup']);
                
            }
        }
        
        $with = $passBuilderName ? array($name) : array();
        
        $uName = ucfirst($name);
        
        $this->builderMethodTest('end'.$uName.'Group', array(), $this->query, null, $with, $methodMap['endConditionGroup']);
        
        $methods = array(
            'add'.$uName.'OperatorCondition' => array('or', true, 'test', '>', array(5)),
            'start'.$uName.'ConditionGroup' => array('or', true),
            'build'.$uName.'Condition' => array('or', true, array('a', 1)),
            'add'.$uName.'Condition' => array('or', true, $this->quickMock('\PHPixie\Database\Conditions\Condition', array())),
        );
        
        foreach($methods as $method => $params) {
            $builderParams = $params;
            if($passBuilderName)
                $builderParams[]=$name;
            
            $builderMethod = str_replace($uName, '', $method);
            $this->builderMethodTest($method, $params, $this->query, null, $builderParams, $methodMap[$builderMethod]);
        }
        
        $container = $this->quickMock('\PHPixie\Database\Conditions\Builder\Container\Conditions', array());
        $params = array('or', true, false);
        $builderParams = $params;
            if($passBuilderName)
                $builderParams[]=$name;
        
        $this->builderMethodTest('add'.$uName.'Placeholder', $params, $container, $container, $builderParams, $methodMap['addPlaceholder']);
        
        if($testContainer){
            $this->builderMethodTest('get'.$uName.'Container', array(), $this->builder, $this->builder, $with, 'conditionContainer');
            $this->builderMethodTest('get'.$uName.'Conditions', array(), array('test'), array('test'), $with, 'getConditions');
        }
        
    }
    
    public function operatorConditionTest($operator, $params, $containers = array(), $prefix = '')
    {
        $params[] = 'or';
        $params[] = true;
        
        $uName = ucfirst($operator);
        $prefix = ucfirst($prefix);
        $builderMethod = "add".$prefix.$uName."OperatorCondition";
        
        $containers[]='';
        
        foreach($containers as $container) {
            $method = $this->builder
                ->expects($this->at(0))
                ->method($builderMethod);
            call_user_func_array(array($method, 'with'), $params);
            $callback = array($this->query, "add".$prefix.ucfirst($container).$uName."OperatorCondition");
            $this->assertSame($this->query, call_user_func_array($callback, $params));
        }
    }
    
    protected function conditionAliasTest()
    {
        $this->builderMethodTest('and', array('test', 1, 2), $this->query,  null, array('and', false, array('test', 1, 2)), 'buildCondition');
        $this->builderMethodTest('or', array('test', 1, 2), $this->query,  null, array('or', false, array('test', 1, 2)), 'buildCondition');
        $this->builderMethodTest('xor', array('test', 1, 2), $this->query,  null, array('xor', false, array('test', 1, 2)), 'buildCondition');
        $this->builderMethodTest('not', array('test', 1, 2), $this->query,  null, array('and', true, array('test', 1, 2)), 'buildCondition');
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
