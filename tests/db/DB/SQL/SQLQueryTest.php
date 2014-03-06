<?php
require_once(ROOT.'/vendor/phpixie/db/tests/db/DB/QueryTest.php');

abstract class SQLQueryTest extends QueryTest
{
    protected $join;
    protected $resultClass;

    public function setUp()
    {
        parent::setUp();
        $this->connection = $this->mockConnection();
    }

    public function testTable()
    {
        $this->assertEquals(null, $this->query->getTable());
        $this->assertEquals($this->query, $this->query->table('a'));
        $this->assertEquals(array('table'=>'a', 'alias' => null), $this->query->getTable());
        $this->assertEquals($this->query, $this->query->table('b', 'c'));
        $this->assertEquals(array('table'=>'b', 'alias' => 'c'), $this->query->getTable());
    }

    public function testGroupBy()
    {
        $this->assertEquals(array(), $this->query->getGroupBy());
        $this->assertEquals($this->query, $this->query->groupBy('id'));
        $this->assertEquals(array('id'), $this->query->getGroupBy());
        $this->assertEquals($this->query, $this->query->groupBy('name'));
        $this->assertEquals(array('id', 'name'), $this->query->getGroupBy());
    }

    public function testJoin()
    {
        $this->query->join('fairies', 'pixies');
        $this->assertEquals(array(
            array(
                'builder' => $this->builder,
                'table' => 'fairies',
                'alias' => 'pixies',
                'type'  => 'inner'
            )
        ), $this->query->getJoins());
        $this->query->join('test', null, 'left');
        $this->assertEquals(array(
            array(
                'builder' => $this->builder,
                'table' => 'fairies',
                'alias' => 'pixies',
                'type'  => 'inner'
            ),
            array(
                'builder' => $this->builder,
                'table' => 'test',
                'alias' =>  null,
                'type'  => 'left'
            )
        ), $this->query->getJoins());
        $this->query->join('test2');
        $this->assertEquals(array(
            array(
                'builder' => $this->builder,
                'table' => 'fairies',
                'alias' => 'pixies',
                'type'  => 'inner'
            ),
            array(
                'builder' => $this->builder,
                'table' => 'test',
                'alias' =>  null,
                'type'  => 'left'
            ),
            array(
                'builder' => $this->builder,
                'table' => 'test2',
                'alias' =>  null,
                'type'  => 'inner'
            )
        ), $this->query->getJoins());
    }

    public function testUnions()
    {
        $this->assertEquals($this->query, $this->query->union($this->query));
        $this->assertEquals(array(
                array($this->query, false)
            ), $this->query->getUnions());

        $this->assertEquals($this->query, $this->query->union($this->query, true));
        $this->assertEquals(array(
                array($this->query, false),
                array($this->query, true)
            ), $this->query->getUnions());
    }

    public function testHaving()
    {
        $this->builderTest('having');
    }

    public function testOn()
    {
        $self = $this;
        $this->pixie->db
                    ->expects($this->any())
                    ->method('condition_builder')
                    ->with('=*')
                    ->will($this->returnCallback(function () use ($self) {
                        return $self->builder;
                    }));
        $this->assertBuilderException(function () {
            $this->query->on('id', 1);
        });
        $this->query->join('fairies');
        $this->builderTest('on', false);
    }

    public function testExecute()
    {
        $query = $this->query();
        $this->parser
                ->expects($this->any())
                ->method('parse')
                ->with ($query)
                ->will($this->returnValue(new \PHPixie\DB\SQL\Expression('pixie', array(5))));

        $this->connection
                ->expects($this->any())
                ->method('execute')
                ->with ('pixie', array(5))
                ->will($this->returnValue('a'));
        $this->assertEquals('a', $query->execute());
    }

    public function testExecuteCount()
    {
        $query = $this->query('count');
        $result = $this->getMock($this->resultClass, array('get'), array(), '', null, false);
        $this->parser
                ->expects($this->any())
                ->method('parse')
                ->with ($query)
                ->will($this->returnValue(new \PHPixie\DB\SQL\Expression('pixie', array(5))));

        $this->connection
                ->expects($this->any())
                ->method('execute')
                ->with ('pixie', array(5))
                ->will($this->returnValue($result));
        $result
                ->expects($this->once())
                ->method('get')
                ->with('count')
                ->will($this->returnValue(5));
        $this->assertEquals(5, $query->execute());
    }

    public function testGenericBuilder()
    {
        $whereBuilder = new BuilderStub();
        $havingBuilder = new BuilderStub();
        $onBuilder1 = new BuilderStub();
        $onBuilder2 = new BuilderStub();
        $this->pixie->db = $this->getMock('\PHPixie\DB', array('condition_builder'), array($this->pixie));
        $this->pixie->db
                    ->expects($this->at(0))
                    ->method('condition_builder')
                    ->will($this->returnCallback(function () use ($whereBuilder) {
                        return $whereBuilder;
                    }));
        $this->pixie->db
                    ->expects($this->at(1))
                    ->method('condition_builder')
                    ->will($this->returnCallback(function () use ($havingBuilder) {
                        return $havingBuilder;
                    }));

        $this->pixie->db
                    ->expects($this->at(2))
                    ->method('condition_builder')
                    ->will($this->returnCallback(function () use ($onBuilder1) {
                        return $onBuilder1;
                    }));

        $this->pixie->db
                    ->expects($this->at(3))
                    ->method('condition_builder')
                    ->will($this->returnCallback(function () use ($onBuilder2) {
                        return $onBuilder2;
                    }));

        $this->assertBuilderException(function () {
            $this->query->_and('a', 1);
        });

        $this->query = $this->query();
        $this->query->where('a', 1);
        $this->assertAttributeEquals($whereBuilder, 'last_used_builder', $this->query);
        $this->genericBuilderTest($whereBuilder);

        $this->query->having('a', 1);
        $this->assertAttributeEquals($havingBuilder, 'last_used_builder', $this->query);
        $this->genericBuilderTest($havingBuilder);

        $this->query->join('pixies');
        $this->query->on('a.id', 'pixies.id');
        $this->assertAttributeEquals($onBuilder1, 'last_used_builder', $this->query);
        $this->genericBuilderTest($onBuilder1);

        $this->query->join('pixies');
        $this->query->on('a.id', 'pixies.id');
        $this->assertAttributeEquals($onBuilder2, 'last_used_builder', $this->query);
        $this->genericBuilderTest($onBuilder2);

    }
}
