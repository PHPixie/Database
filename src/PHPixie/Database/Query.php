<?php

namespace PHPixie\Database;

interface Query
{
    public function getType();
    public function parse();
    public function execute();
}
