<?php

namespace PHPixie\Database\Type\Document\Conditions\Condition\Collection;

class Placeholder extends \PHPixie\Database\Conditions\Condition\Collection\Placeholder
{
    public function __construct($container, $allowEmpty = true)
    {
        parent::__construct($container, $allowEmpty);    
    }
    
    public function container()
    {
        return parent::container();
    }
}