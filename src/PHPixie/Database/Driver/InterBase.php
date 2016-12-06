<?php
namespace PHPixie\Database\Driver;

/**
 * Class InterBase
 * @package PHPixie\Database\Driver
 */
class InterBase extends \PHPixie\Database\Driver\PDO
{
    /**
     * @param string $name
     * @param        $config
     * @param        $connection
     * @return InterBase\Adapter\Firebird
     */
    public function adapter($name, $config, $connection)
    {
        $class = '\PHPixie\Database\Driver\InterBase\Adapter\\' . ucfirst($name);

        return new $class($config, $connection);
    }

    /**
     * @param $connectionName
     * @param $config
     * @return InterBase\Connection
     */
    public function buildConnection($connectionName, $config)
    {
        return new InterBase\Connection($this, $connectionName, $config);
    }

    /**
     * @param string                                       $adapterName
     * @param \PHPixie\Slice\Type\ArrayData                $config
     * @param InterBase\Adapter\Firebird\Parser\Fragment   $fragmentParser
     * @param InterBase\Adapter\Firebird\Parser\Conditions $conditionsParser
     * @return InterBase\Adapter\Firebird\Parser
     */
    public function buildParser($adapterName, $config, $fragmentParser, $conditionsParser)
    {
        $class = '\PHPixie\Database\Driver\InterBase\Adapter\\' . ucfirst($adapterName) . '\Parser';

        return new $class($this->database, $this, $config, $fragmentParser, $conditionsParser);
    }

    /**
     * @param string                                     $adapterName
     * @param \PHPixie\Database\Type\SQL\Parser\Operator $operatorParser
     * @return InterBase\Adapter\Firebird\Parser\Conditions
     */
    public function conditionsParser($adapterName, $operatorParser)
    {
        $class = '\PHPixie\Database\Driver\InterBase\Adapter\\' . ucfirst($adapterName) . '\Parser\Conditions';

        return new $class($this->database, $operatorParser);
    }

    /**
     * @param string $adapterName
     * @return InterBase\Adapter\Firebird\Parser\Fragment
     */
    public function fragmentParser($adapterName)
    {
        $class = '\PHPixie\Database\Driver\InterBase\Adapter\\' . ucfirst($adapterName) . '\Parser\Fragment';

        return new $class;
    }

    /**
     * @param string                                     $adapterName
     * @param InterBase\Adapter\Firebird\Parser\Fragment $fragmentParser
     * @return \PHPixie\Database\Type\SQL\Parser\Operator
     */
    public function operatorParser($adapterName, $fragmentParser)
    {
        $class = '\PHPixie\Database\Driver\InterBase\Adapter\\' . ucfirst($adapterName) . '\Parser\Operator';

        return new $class($this->database, $fragmentParser);
    }

}
