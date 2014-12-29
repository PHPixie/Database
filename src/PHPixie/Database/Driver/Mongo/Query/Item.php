<?php

namespace PHPixie\Database\Driver\Mongo\Query;

abstract class Item extends \PHPixie\Database\Driver\Mongo\Query 
                    implements \PHPixie\Database\Type\Document\Conditions\Builder
{
    public function __construct($connection, $parser, $builder)
    {
        parent::__construct($connection, $parser, $builder);

        $this->aliases = array_merge($this->aliases, array(
            'and' => '_and',
            'or'  => '_or',
            'xor' => '_xor',
            'not' => '_not',
        ));
    }
    
    protected function addContainerOperatorCondition($logic, $negate, $field, $operator, $values, $containerName = null)
    {
        $this->builder->addOperatorCondition($logic, $negate, $field, $operator, $values, $containerName);
        
        return $this;
    }
    
    protected function addContainerCondition($logic, $negate, $args, $containerName = null)
    {
        $this->builder->addCondition($logic, $negate, $args, $containerName);

        return $this;
    }

    protected function startContainerConditionGroup($logic = 'and', $negate = false, $containerName = null)
    {
        $this->builder->startConditionGroup($logic, $negate, $containerName);

        return $this;
    }

    protected function endContainerConditionGroup($containerName = null)
    {
        $this->builder->endConditionGroup($containerName);

        return $this;
    }
    
    protected function addContainerPlaceholder($logic = 'and', $negate = false, $allowEmpty = true, $containerName = null)
    {
        return $this->builder->addPlaceholder($logic, $negate, $allowEmpty, $containerName);
    }
    
    protected function addContainerSubdocumentPlaceholder($field, $logic = 'and', $negate = false, $allowEmpty = true, $containerName = null)
    {
        return $this->builder->addSubdocumentPlaceholder($field, $logic, $negate, $allowEmpty, $containerName);
    }
    
    protected function addContainerSubarrayItemPlaceholder($field, $logic = 'and', $negate = false, $allowEmpty = true, $containerName = null)
    {
        return $this->builder->addSubarrayItemPlaceholder($field, $logic, $negate, $allowEmpty, $containerName);
    }

    public function addCondition($logic, $negate, $params)
    {
        $this->builder->addCondition($logic, $negate, $params);
        return $this;
    }
    
    public function addWhereCondition($logic, $negate, $params)
    {
        return $this->addContainerCondition($logic, $negate, $params, 'where');
    }
    
    public function getWhereContainer()
    {
        return $this->builder->conditionContainer('where');
    }

    public function getWhereConditions()
    {
        return $this->builder->getConditions('where');
    }
    
    public function addOperatorCondition($logic, $negate, $field, $operator, $values)
    {
        return $this->addContainerOperatorCondition($logic, $negate, $field, $operator, $values);
    }
    
    public function addWhereOperatorCondition($logic, $negate, $field, $operator, $values)
    {
        return $this->addContainerOperatorCondition($logic, $negate, $field, $operator, $values, 'where');
    }

    public function startConditionGroup($logic = 'and', $negate = false)
    {
        return $this->startContainerConditionGroup($logic, $negate);
    }
    
    public function startWhereConditionGroup($logic = 'and', $negate = false)
    {
        return $this->startContainerConditionGroup($logic, $negate, 'where');
    }

    public function addPlaceholder($logic = 'and', $negate = false, $allowEmpty = true)
    {
        return $this->addContainerPlaceholder($logic, $negate, $allowEmpty);
    }
    
    public function addWherePlaceholder($logic = 'and', $negate = false, $allowEmpty = true)
    {
        return $this->addContainerPlaceholder($logic, $negate, $allowEmpty, 'where');
    }
    
    public function addSubdocumentPlaceholder($field, $logic = 'and', $negate = false, $allowEmpty = true)
    {
        return $this->addContainerSubdocumentPlaceholder($field, $logic, $negate, $allowEmpty);
    }
    
    public function addWhereSubdocumentPlaceholder($field, $logic = 'and', $negate = false, $allowEmpty = true)
    {
        return $this->addContainerSubdocumentPlaceholder($field, $logic, $negate, $allowEmpty, 'where');
    }
    
    public function addSubarrayItemPlaceholder($field, $logic = 'and', $negate = false, $allowEmpty = true)
    {
        return $this->addContainerSubarrayItemPlaceholder($field, $logic, $negate, $allowEmpty);
    }
    
    public function addWhereSubarrayItemPlaceholder($field, $logic = 'and', $negate = false, $allowEmpty = true)
    {
        return $this->addContainerSubarrayItemPlaceholder($field, $logic, $negate, $allowEmpty, 'where');
    } 

    public function where()
    {
        return $this->addContainerCondition('and', false, func_get_args(), 'where');
    }

    public function andWhere()
    {
        return $this->addContainerCondition('and', false, func_get_args(), 'where');
    }

    public function orWhere()
    {
        return $this->addContainerCondition('or', false, func_get_args(), 'where');
    }

    public function xorWhere()
    {
        return $this->addContainerCondition('xor', false, func_get_args(), 'where');
    }

    public function whereNot()
    {
        return $this->addContainerCondition('and', true, func_get_args(), 'where');
    }

    public function andWhereNot()
    {
        return $this->addContainerCondition('and', true, func_get_args(), 'where');
    }

    public function orWhereNot()
    {
        return $this->addContainerCondition('or', true, func_get_args(), 'where');
    }

    public function xorWhereNot()
    {
        return $this->addContainerCondition('xor', true, func_get_args(), 'where');
    }

    public function startWhereGroup()
    {
        return $this->startContainerConditionGroup('and', false, 'where');
    }

    public function startAndWhereGroup()
    {
        return $this->startContainerConditionGroup('and', false, 'where');
    }

    public function startOrWhereGroup()
    {
        return $this->startContainerConditionGroup('or', false, 'where');
    }

    public function startXorWhereGroup()
    {
        return $this->startContainerConditionGroup('xor', false, 'where');
    }

    public function startWhereNotGroup()
    {
        return $this->startContainerConditionGroup('and', true, 'where');
    }

    public function startAndWhereNotGroup()
    {
        return $this->startContainerConditionGroup('and', true, 'where');
    }

    public function startOrWhereNotGroup()
    {
        return $this->startContainerConditionGroup('or', true, 'where');
    }

    public function startXorWhereNotGroup()
    {
        return $this->startContainerConditionGroup('xor', true, 'where');
    }

    public function endWhereGroup()
    {
        return $this->endContainerConditionGroup('where');
    }

    public function _and()
    {
        return $this->addContainerCondition('and', false, func_get_args());
    }

    public function _or()
    {
        return $this->addContainerCondition('or', false, func_get_args());
    }

    public function _xor()
    {
        return $this->addContainerCondition('xor', false, func_get_args());
    }

    public function _not()
    {
        return $this->addContainerCondition('and', true, func_get_args());
    }

    public function andNot()
    {
        return $this->addContainerCondition('and', true, func_get_args());
    }

    public function orNot()
    {
        return $this->addContainerCondition('or', true, func_get_args());
    }

    public function xorNot()
    {
        return $this->addContainerCondition('xor', true, func_get_args());
    }

    public function startGroup()
    {
        return $this->startConditionGroup('and', false);
    }

    public function startAndGroup()
    {
        return $this->startConditionGroup('and', false);
    }

    public function startOrGroup()
    {
        return $this->startConditionGroup('or', false);
    }

    public function startXorGroup()
    {
        return $this->startConditionGroup('xor', false);
    }

    public function startNotGroup()
    {
        return $this->startConditionGroup('and', true);
    }

    public function startAndNotGroup()
    {
        return $this->startConditionGroup('and', true);
    }

    public function startOrNotGroup()
    {
        return $this->startConditionGroup('or', true);
    }

    public function startXorNotGroup()
    {
        return $this->startConditionGroup('xor', true);
    }

    public function endGroup()
    {
        return $this->endContainerConditionGroup();
    }
}
