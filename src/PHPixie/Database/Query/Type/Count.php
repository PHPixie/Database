<?php

namespace PHPixie\Database\Query\Type;

interface Count extends \PHPixie\Database\Query\Items{
    public function execute();
}