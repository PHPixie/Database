<?php

namespace PHPixieTests\Database\Driver\Mongo\Parser;

if(!class_exists('\MongoRegex'))
    require_once(__DIR__.'/OperatorTestFiles/MongoRegex.php');

if(!class_exists('\MongoId'))
    require_once(__DIR__.'/OperatorTestFiles/MongoId.php');

/**
 * @coversDefaultClass \PHPixie\Database\Driver\Mongo\Parser\Operator
 */
class OperatorTest extends \PHPixieTests\AbstractDatabaseTest
{
    protected $parser;
    protected $mongoId = '49a702d5450046d3d515d10d';
    
    protected function setUp()
    {
        $this->parser = new \PHPixie\Database\Driver\Mongo\Parser\Operator;
    }

    /**
     * @covers ::__construct
     * @covers ::parse
     * @covers ::<protected>
     */
    public function testParseSimple()
    {
        $operators = array(
            '<'  => '$lt',
            '<=' => '$lte',
            '!=' => '$ne',
            'ne' => '$ne',
            'exists' => '$exists'
        );

        foreach($operators as $operator => $result)
            $this->assertOperator(
                array('p' => array($result => 5)),
                $operator
            );

        $this->assertOperator(
            array('p' => 5),
            '='
        );

        $this->assertOperator(
            array(
                'p' => array(
                    '$gte' => 3,
                    '$lte' => 4,
                )
            ),
            'between', false, array(3,4)
        );

        $this->assertOperator(
            array(
                'p' => array(
                    '$lt' => 3,
                    '$gt' => 4,
                )
            ),
            'not between', false, array(3,4)
        );
    }

    /**
     * @covers ::__construct
     * @covers ::parse
     * @covers ::<protected>
     */
    public function testParseNegated()
    {
        $operators = array(
            '>'  => '$lte',
            '>=' => '$lt'
        );

        foreach($operators as $operator => $result)
            $this->assertOperator(
                array('p' => array($result => 5)),
                $operator, true
            );

        $this->assertOperator(array('p' => 5), 'ne', true);
        $this->assertOperator(array('p' => 5), '!=', true);
        $this->assertOperator(
            array('p' =>
                array('$not' => array(
                    '$exists' => 5
                ))
            ),
            'exists',true
        );

        $this->assertOperator(
            array(
                'p' => array(
                    '$gt' => 4,
                    '$lt' => 3,
                )
            ),
            'between', true, array(3,4)
        );
    }

    /**
     * @covers ::parse
     * @covers ::<protected>
     */
    public function testIn()
    {
        $this->assertOperator(array('p' => array('$in' => array(6))), 'in', false,  array(array(6)));
        $this->assertOperator(array('p' => array('$nin' => array(6))), 'in', true,  array(array(6)));
        $this->assertOperator(array('p' => array('$in' => array(6))), 'not in', true, array(array(6)));
        $this->assertOperator(array('p' => array('$nin' => array(6))), 'not in', false,  array(array(6)));
    }
    
    /**
     * @covers ::parse
     * @covers ::<protected>
     */
    public function testParseMongoId()
    {
        foreach(array(true, false) as $convertMongoId) {
            
            $mongoId = $this->mongoId;
            if($convertMongoId) {
                $mongoId =  new \MongoId($mongoId);
            }
            
            
            $this->assertIdOperator(
                array(
                    '_id' => $mongoId
                ),
                '=', false,  array($this->mongoId),
                $convertMongoId
            );

            $this->assertIdOperator(
                array(
                    '_id' => array(
                        '$nin' => array($mongoId)
                    )
                ),
                'not in', false,  array(array($this->mongoId)),
                $convertMongoId
            );

            $this->assertIdOperator(
                array(
                    '_id' => array(
                        '$lt' => $mongoId,
                        '$gt' => $mongoId,
                    )
                ),
                'between', true, array($this->mongoId, $this->mongoId),
                $convertMongoId
            );
        }
    }
    
    /**
     * @covers ::parse
     * @covers ::<protected>
     */
    public function testParseMongoIdException()
    {
        $this->assertException('exists', array('test'), '_id');
        
        $this->assertIdOperator(
            array('_id' => array('$exists' => 5)),
            'exists', false, array(5), false
        );
    }

    /**
     * @covers ::parse
     * @covers ::<protected>
     */
    public function testRegex()
    {
        $operator = new \PHPixie\Database\Conditions\Condition\Field\Operator('p', 'regex', array('/la/'));
        $parsed = $this->parser->parse($operator);
        $this->assertEquals('p', key($parsed));
        $parsed = $parsed['p'];
        $this->assertEquals('$regex', key($parsed));
        $this->assertEquals('MongoRegex', get_class(current($parsed)));

        $operator = new \PHPixie\Database\Conditions\Condition\Field\Operator('p', 'regex', array('/la/'));
        $operator->negate();
        $parsed = $this->parser->parse($operator);
        $this->assertEquals('p', key($parsed));
        $parsed = $parsed['p'];
        $parsed = $parsed['$not'];
        $this->assertEquals('$regex', key($parsed));
        $this->assertEquals('MongoRegex', get_class(current($parsed)));

        $operator = new \PHPixie\Database\Conditions\Condition\Field\Operator('p', 'not regex', array('/la/'));
        $parsed = $this->parser->parse($operator);
        $this->assertEquals('p', key($parsed));
        $parsed = $parsed['p'];
        $parsed = $parsed['$not'];
        $this->assertEquals('$regex', key($parsed));
        $this->assertEquals('MongoRegex', get_class(current($parsed)));

        $operator = new \PHPixie\Database\Conditions\Condition\Field\Operator('p', 'not regex', array('/la/'));
        $operator->negate();
        $parsed = $this->parser->parse($operator);
        $this->assertEquals('p', key($parsed));
        $parsed = $parsed['p'];
        $this->assertEquals('$regex', key($parsed));
        $this->assertEquals('MongoRegex', get_class(current($parsed)));
    }

    /**
     * @covers ::parse
     * @covers ::<protected>
     */
    public function testException()
    {
        $this->assertException('between', 6);
        $this->assertException('between', array(6));
        $this->assertException('between', 'in', 7);
        $this->assertException('between', 'in', array(6),7);
    }

    protected function assertException($operator, $value, $field = 'p', $convertMongoId = true)
    {
        $except = false;
        $o = new \PHPixie\Database\Conditions\Condition\Field\Operator($field, $operator, $value);
        try {
            $this->parser->parse($o, $convertMongoId);
        } catch (\PHPixie\Database\Exception\Parser $e) {
            $except = true;
        };
        $this->assertEquals($except, true);
    }

    protected function assertOperator($result, $operator, $negated = false, $value = array(5))
    {
        $operator = new \PHPixie\Database\Conditions\Condition\Field\Operator('p', $operator, $value);
        if ($negated)
            $operator->negate();
        $this->assertEquals($result, $this->parser->parse($operator));
    }
    
    protected function assertIdOperator($result, $operator, $negated, $value, $convertMongoId = true)
    {
        $operator = new \PHPixie\Database\Conditions\Condition\Field\Operator('_id', $operator, $value);
        if ($negated)
            $operator->negate();
        
        $this->assertEqualsWithMongoId($result, $this->parser->parse($operator, $convertMongoId));
    }
    
    protected function assertEqualsWithMongoId($left, $right)
    {
        $this->assertSame(array_keys($left), array_keys($right));
        
        foreach($left as $key => $value) {
            if($value instanceof \MongoId) {
                $this->assertInstanceOf('\MongoId', $right[$key]);
                $this->assertEquals((string) $value, (string) $right[$key]);
                
            }elseif(is_array($value)) {
                $this->assertEquals(true, is_array($right[$key]));
                $this->assertEqualsWithMongoId($value, $right[$key]);
                
            }else{
                $this->assertEquals($value, $right[$key]);
            }
        }
    }

}
