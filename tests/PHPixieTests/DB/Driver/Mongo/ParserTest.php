<?php
namespace PHPixieTests\DB\Driver\Mongo;

/**
 * @coversDefaultClass \PHPixie\DB\Driver\Mongo\Parser
 */
class ParserTest extends \PHPixieTests\DB\ParserTest
{
    protected $db;

    protected function setUp()
    {
        $this->db = new \PHPixie\DB(null);
        $driver = $this->db->driver('Mongo');
        $operatorParser = new \PHPixie\DB\Driver\Mongo\Parser\Operator();
        $groupParser = new \PHPixie\DB\Driver\Mongo\Parser\Group($driver, $operatorParser);
        $this->parser = new \PHPixie\DB\Driver\Mongo\Parser($this->db, $driver, 'default', $groupParser);
    }

    /**
     * @covers ::parse
     */
    public function testParseSelect()
    {
        $query = $this->getQuery()->collection('fairies')
                                    ->where('name', 1)
                                    ->where(function ($builder) {
                                        $builder->_and('id', 2)->_or('id', 3);
                                    });
        $this->assertQuery($query, array (
            array (
                'type' => 'property',
                'name' => 'fairies',
            ),
            array (
                'type' => 'method',
                'name' => 'find',
                'args' =>array (
                    array (
                        '$or' => array (
                            array (
                                'name' => 1,
                                'id' => 2,
                            ),
                            array (
                                'name' => 1,
                                'id' => 3,
                            ),
                        ),
                    ),
                    array()
                )
            )
        ));

        $query = $this->getQuery()->collection('fairies')
                                    ->fields(array('id', 'name'))
                                    ->where('name', 1)
                                    ->limit(4)
                                    ->offset(10);

        $this->assertQuery($query, array (
            array (
                'type' => 'property',
                'name' => 'fairies',
            ),
            array (
                'type' => 'method',
                'name' => 'find',
                'args' =>array (
                    array (
                        'name' => 1
                    ),
                    array(
                        'id', 'name'
                    )
                )
            ),
            array(
                'type' => 'method',
                'name' => 'limit',
                'args' => array(4)
            ),
            array(
                'type' => 'method',
                'name' => 'skip',
                'args' => array(10)
            )
        ));

        $query = $this->getQuery()->collection('fairies')
                                    ->limit(1);

        $this->assertQuery($query, array (
            array (
                'type' => 'property',
                'name' => 'fairies',
            ),
            array (
                'type' => 'method',
                'name' => 'findOne',
                'args' => array (array(), array())
            )
        ));
    }

    /**
     * @covers ::parse
     */
    public function testParseInsert()
    {
        $query = $this->getQuery('insert')->collection('fairies')
                                    ->data(array('id'=>1, 'name'=>"Trixie"));

        $this->assertQuery($query, array (
            array (
                'type' => 'property',
                'name' => 'fairies',
            ),
            array (
                'type' => 'method',
                'name' => 'insert',
                'args' => array (array('id'=>1, 'name'=>"Trixie"))
            )
        ));
    }

    /**
     * @covers ::parse
     */
    public function testParseUpdate()
    {
        $query = $this->getQuery('update')->collection('fairies')
                                    ->data(array('id'=>1, 'name'=>"Trixie"));

        $this->assertQuery($query, array (
            array (
                'type' => 'property',
                'name' => 'fairies',
            ),
            array (
                'type' => 'method',
                'name' => 'update',
                'args' => array (array(), array('id'=>1, 'name'=>"Trixie"), array('multiple' => true))
            )
        ));

        $query = $this->getQuery('update')->collection('fairies')
                                    ->where('name', 5)
                                    ->data(array('id'=>1, 'name'=>"Trixie"));

        $this->assertQuery($query, array (
            array (
                'type' => 'property',
                'name' => 'fairies',
            ),
            array (
                'type' => 'method',
                'name' => 'update',
                'args' => array (array('name' => 5), array('id'=>1, 'name'=>"Trixie"), array('multiple' => true))
            )
        ));
    }

    /**
     * @covers ::parse
     */
    public function testParseDelete()
    {
        $query = $this->getQuery('delete')->collection('fairies')
                                    ->where('id',7);

        $this->assertQuery($query, array (
            array (
                'type' => 'property',
                'name' => 'fairies',
            ),
            array (
                'type' => 'method',
                'name' => 'remove',
                'args' => array (array('id'=>7))
            )
        ));

        $query = $this->getQuery('delete')->collection('fairies');

        $this->assertQuery($query, array (
            array (
                'type' => 'property',
                'name' => 'fairies',
            ),
            array (
                'type' => 'method',
                'name' => 'remove',
                'args' => array (array())
            )
        ));

    }

    /**
     * @covers ::parse
     */
    public function testParseCount()
    {
        $query = $this->getQuery('count')->collection('fairies')
                                    ->where('id',7);

        $this->assertQuery($query, array (
            array (
                'type' => 'property',
                'name' => 'fairies',
            ),
            array (
                'type' => 'method',
                'name' => 'find',
                'args' => array (array('id'=>7))
            ),
            array (
                'type' => 'method',
                'name' => 'count',
                'args' => array()
            )
        ));

        $query = $this->getQuery('count')->collection('fairies');

        $this->assertQuery($query, array (
            array (
                'type' => 'property',
                'name' => 'fairies',
            ),
            array (
                'type' => 'method',
                'name' => 'count',
                'args' => array()
            )
        ));

    }

    /**
     * @covers ::parse
     */
    public function testException()
    {
        $this->assertException($this->getQuery('insert'));
        $this->assertException($this->getQuery('select'));
        $this->assertException($this->getQuery('insert')->data(array('id'=>1)));
        $this->assertException($this->getQuery('insert')->collection('fairies'));
        $this->assertException($this->getQuery('update')->collection('fairies'));

    }

    protected function assertException($query)
    {
        $this->setExpectedException('\PHPixie\DB\Exception\Parser');
        $this->parser->parse($query);
    }

    protected function assertQuery($query, $expect)
    {
        $chain = $this->parser->parse($query)->getChain();
        $this->assertEquals($chain, $expect);
    }

    protected function getQuery($type = 'select')
    {
        $query = new \PHPixie\DB\Driver\Mongo\Query($this->db, $this->db->conditions(), null, null, null, $type);

        return $query;
    }

}
