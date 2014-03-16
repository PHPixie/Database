<?php

namespace PHPixie\Database\Driver\Mongo\Query;

class Runner
{
    protected $chain = array();
    public function chainProperty($property)
    {
        $this->chain[] = array(
            'type' => 'property',
            'name' => $property
        );
    }

    public function chainMethod($method, $args = array())
    {
        $this->chain[] = array(
            'type' => 'method',
            'name' => $method,
            'args' => $args
        );
    }

    public function getChain()
    {
        return $this->chain;
    }

    public function run($connection)
    {
        $current = $connection->database();
        foreach ($this->chain as $step) {
            switch ($step['type']) {
                case 'property':
                    $property = $step['name'];
                    $current = $current->$property;
                    break;
                case 'method':
                    $args = $step['args'];
                    $current = call_user_func_array(array($current, $step['name']), $args);

                    if ($step['name'] === 'insert')
                        $connection->setInsertId((string) $args[0]['_id']);
                        
                    if ($step['name'] === 'batchInsert') {
                        $last = end($args[0]);
                        $connection->setInsertId((string) $last['_id']);
                    }

                    break;
            }
        }

        return $current;
    }
}
