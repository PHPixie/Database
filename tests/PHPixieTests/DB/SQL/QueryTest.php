<?php
namespace PHPixieTests\DB\SQL;

/**
 * @coversDefaultClass \PHPixie\DB\SQL\Query
 */
abstract class QueryTest extends \PHPixieTests\DB\QueryTest
{
    protected $join;
    protected $resultClass;

    public function setUp()
    {
        parent::setUp();
        $this->connection = $this->mockConnection();
    }

    /**
     * @covers ::table
     * @covers ::getTable
     */
    public function testTable()
    {
        $this->assertEquals(null, $this->query->getTable());
        $this->assertEquals($this->query, $this->query->table('a'));
        $this->assertEquals(array('table'=>'a', 'alias' => null), $this->query->getTable());
        $this->assertEquals($this->query, $this->query->table('b', 'c'));
        $this->assertEquals(array('table'=>'b', 'alias' => 'c'), $this->query->getTable());
    }

    /**
     * @covers ::groupBy
     * @covers ::getGroupBy
     */
    public function testGroupBy()
    {
        $this->assertEquals(array(), $this->query->getGroupBy());
        $this->assertEquals($this->query, $this->query->groupBy('id'));
        $this->assertEquals(array('id'), $this->query->getGroupBy());
        $this->assertEquals($this->query, $this->query->groupBy('name'));
        $this->assertEquals(array('id', 'name'), $this->query->getGroupBy());
    }

    /**
     * @covers ::join
     * @covers ::getJoins
     */
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

    /**
     * @covers ::union
     * @covers ::getUnions
     */
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

    /**
     * @covers ::having
     * @covers ::orHaving
     * @covers ::xorHaving
     * @covers ::andHavingNot
     * @covers ::orHavingNot
     * @covers ::xorHavingNot
     * @covers ::startHavingGroup
     * @covers ::endHavingGroup
     */
    public function testHaving()
    {
        $this->builderTest('having');
    }

    /**
     * @covers ::on
     * @covers ::orOn
     * @covers ::xorOn
     * @covers ::andOnNot
     * @covers ::orOnNot
     * @covers ::xorOnNot
     * @covers ::startOnGroup
     * @covers ::endOnGroup
     */
    public function testOn()
    {
        $self = $this;
        $this->db
                ->expects($this->any())
                ->method('conditionBuilder')
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

    /**
     * @covers ::execute
     */
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

    /**
     * @covers ::execute
     */
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

    /**
     * @covers ::_and
     * @covers ::_or
     * @covers ::_xor
     * @covers ::_andNot
     * @covers ::_orNot
     * @covers ::_xorNot
     * @covers ::startGroup
     * @covers ::endGroup
     */
    public function testGenericBuilder()
    {
        $whereBuilder = new BuilderStub();
        $havingBuilder = new BuilderStub();
        $onBuilder1 = new BuilderStub();
        $onBuilder2 = new BuilderStub();
        $this->conditionsMock = $this->getMock('\PHPixie\DB\Conditions', array('builder'));
        $this->conditionsMock
                    ->expects($this->at(0))
                    ->method('builder')
                    ->will($this->returnCallback(function () use ($whereBuilder) {
                        return $whereBuilder;
                    }));
        $this->conditionsMock
                    ->expects($this->at(1))
                    ->method('builder')
                    ->will($this->returnCallback(function () use ($havingBuilder) {
                        return $havingBuilder;
                    }));

        $this->conditionsMock
                    ->expects($this->at(2))
                    ->method('builder')
                    ->will($this->returnCallback(function () use ($onBuilder1) {
                        return $onBuilder1;
                    }));

        $this->conditionsMock
                    ->expects($this->at(3))
                    ->method('builder')
                    ->will($this->returnCallback(function () use ($onBuilder2) {
                        return $onBuilder2;
                    }));

        $this->assertBuilderException(function () {
            $this->query->_and('a', 1);
        });

        $this->query = $this->query();
        $this->query->where('a', 1);
        $this->assertAttributeEquals($whereBuilder, 'lastUsedBuilder', $this->query);
        $this->genericBuilderTest($whereBuilder);

        $this->query->having('a', 1);
        $this->assertAttributeEquals($havingBuilder, 'lastUsedBuilder', $this->query);
        $this->genericBuilderTest($havingBuilder);

        $this->query->join('pixies');
        $this->query->on('a.id', 'pixies.id');
        $this->assertAttributeEquals($onBuilder1, 'lastUsedBuilder', $this->query);
        $this->genericBuilderTest($onBuilder1);

        $this->query->join('pixies');
        $this->query->on('a.id', 'pixies.id');
        $this->assertAttributeEquals($onBuilder2, 'lastUsedBuilder', $this->query);
        $this->genericBuilderTest($onBuilder2);

    }
}
