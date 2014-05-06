<?php

namespace PHPixie\Database\SQL\Query\Type;

interface Select extends \PHPixie\Database\SQL\Query\Items implements \PHPixie\Database\Query\Type\Select
{
    protected $groupBy = array();
    protected $unions = array();
    

}