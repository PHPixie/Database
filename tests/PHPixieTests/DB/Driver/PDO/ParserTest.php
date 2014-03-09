<?php
namespace PHPixieTests\DB\Driver\PDO;

/**
 * @coversDefaultClass \PHPixie\DB\Driver\PDO\Parser
 */
abstract class ParserTest extends \PHPixieTests\DB\SQL\ParserTest
{
    protected $adapter;

    protected function setUp()
    {
        parent::setUp();
        $this->parser = $this->parser();
    }

    
    protected function parser()
    {
        $driver = $this->db->driver('PDO');
        $fragmentParser = $driver->fragmentParser($this->adapter);
        $operatorParser = $driver->operatorParser($this->adapter, $fragmentParser);
        $groupParser    = $driver->groupParser($this->adapter, $operatorParser);

        return $driver->buildParser($this->adapter, null, $fragmentParser, $groupParser);
    }

    protected function query($type)
    {
        $query = $this->getMock('\PHPixie\DB\Driver\PDO\Query', array('parse'), array($this->db,$this->db->conditions(), null, null, null, $type));
        $query
            ->expects($this->any())
            ->method('parse')
            ->will($this->returnCallback(function () use ($query) {
                return $this->parser->parse($query);
            }));

        return $query;
    }
}
