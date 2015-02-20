<?php
namespace PHPixieTests\Database\Type\SQL;

/**
 * @coversDefaultClass \PHPixie\Database\Type\SQL\Parser
 */
abstract class ParserTest extends AbstractParserTest
{
    protected $database;
    protected $parser;
    protected $expected;

    protected function setUp()
    {
        $this->database = new \PHPixie\Database(null);
    }

    protected function queries()
    {
        $queries = array(
            $this->query('select')->table('fairies'),

            $this->query('select')->fields(array(
                'id',
                'test' => 'pixie'
            ))->table('fairies'),

            $this->query('select')->table('fairies')->where('a', 1)
                                                    ->orWhere(function ($builder) {
                                                        $builder->_and('b', 1)
                                                                ->_xor('c', 1);
                                                    })->where('d', 1),

            $this->query('select')->table('fairies')
                                                    ->where('a', 1)
                                                    ->having('b', 1)
                                                    ->limit(7)
                                                    ->offset(9)
                                                    ->orderDescendingBy('id')
                                                    ->orderAscendingBy('name')
                                                    ->groupBy('id')
                                                    ->groupBy('name'),

            $this->query('select')
                                ->table($this->query('select')->table('fairies'), 'b')
                                ->join('pixies')
                                ->on('b.id', 'pixies.id')
                                ->union($this->query('select')->table('pixies'), true),

            $this->query('select')
                                ->table($this->database->sqlExpression('test1', array(1)), 'b')
                                ->join($this->database->sqlExpression('test2', array(2)), 'c', 'left_outer')
                                ->on('b.id', 'c.id')
                                ->union($this->database->sqlExpression('test3', array(3))),

            $this->query('update')
                                ->table('fairies')
                                ->set(array(
                                    'id'   => 3,
                                    'name' => 'Trixie'
                                ))
                                ->join('pixies')
                                ->on('fairies.id', 'pixies.id')
                                ->where('id', 7)
                                ->orderAscendingBy('id')
                                ->limit(6)
                                ->offset(7),
            
            $this->query('update')
                                ->table('fairies')
                                ->increment(array(
                                    'trees'   => 3,
                                    'forests' => -1
                                )),

            $this->query('update')
                                ->table('fairies')
                                ->set('name', 'Trixie')
                                ->increment(array(
                                    'trees'   => 3,
                                    'forests' => -1
                                )),
            
            $this->query('insert')
                                ->table('fairies')
                                ->data(array(
                                    'id'   => 3,
                                    'name' => 'Trixie'
                                )),

            $this->query('delete')
                                ->table('fairies')
                                ->where('id', 7)
                                ->orderAscendingBy('id')
                                ->limit(6)
                                ->offset(7),
            
            $this->query('delete')
                                ->table('fairies')
                                ->limit(6),
            
            $this->query('delete')
                                ->table('fairies')
                                ->offset(6),
            
            $this->query('count')
                                ->table('fairies')
                                ->where('a', 1)
                                ->limit(7)
                                ->offset(9)
                                ->orderDescendingBy('id')
                                ->orderAscendingBy('name'),

            $this->query('insert')
                                ->table('fairies'),

            $this->query('insert')
                                ->table('fairies')
                                ->batchData(array('pixie', 'fairy'), array(array(1, 2))),

            $this->query('insert')
                                ->table('fairies')
                                ->batchData(array('pixie', 'fairy'), array(array(1, 2), array(1, 2))),

            $this->query('insert')
                                ->table('fairies')
                                ->batchData(array(), array()),

        );

        return $queries;
    }

    /**
     * @covers ::parse
     * @covers ::__construct
     * @covers ::<protected>
     */
    public function testParse()
    {
        foreach ($this->queries() as $key => $query) {
            $parsed = $this->parser->parse($query);
            $this->assertExpression($parsed, $this->expected[$key]);
        }
    }

    /**
     * @covers ::parse
     * @covers ::<protected>
     */
    public function testExceptions()
    {
        foreach ($this->exceptionQueries() as $key=>$query) {
            $except = false;
            try {
                $parsed = $this->parser->parse($query);
            } catch (\PHPixie\Database\Exception\Parser $e) {
                $except = true;
            }
            $this->assertEquals(true, $except);
        }
    }

    protected function exceptionQueries()
    {
        $pixieQuery = $this->quickMock('\PHPixie\Database\Driver\PDO\Query', array('type'));
        $pixieQuery
            ->expects($this->any())
            ->method('type')
            ->will($this->returnValue('pixie'));
        $queries = array(
            $pixieQuery,

            $this->query('update')
                                ->set(array('id' => 1)),

            $this->query('insert')
                                ->data(array('id' => 1)),

            $this->query('delete')
                                ->where('id', 1),

            $this->query('count')
                                ->where('id', 1),

            $this->query('update')
                                ->table('fairies'),

            $this->query('select')
                                ->table('fairies')
                                ->join('pixies', null, 'test'),

            $this->query('select')
                                ->union('a'),

            $this->query('insert')
                                ->table('fairies')
                                ->batchData(array('pixie', 'fairy'), array(array(1, 2, 3), array(array(1, 2)))),

        );

        return $queries;
    }

    abstract protected function query($type);

}
