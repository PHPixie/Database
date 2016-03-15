<?php

namespace PHPixie\Database\Conditions;

interface Builder
{
    /**
     * @return self
     */
    public function addCondition($logic, $negate, $condition);

    /**
     * @return self
     */
    public function buildCondition($logic, $negate, $params);

    /**
     * @return self
     */
    public function addOperatorCondition($logic, $negate, $field, $operator, $values);

    /**
     * @return self
     */
    public function startConditionGroup($logic = 'and', $negate = false);

    /**
     * @return self
     */
    public function endGroup();

    /**
     * @return self
     */
    public function addPlaceholder($logic = 'and', $negate = false, $allowEmpty = true);

    /**
     * @return self
     */
    public function _and();

    /**
     * @return self
     */
    public function _or();

    /**
     * @return self
     */
    public function _xor();

    /**
     * @return self
     */
    public function _not();

    /**
     * @return self
     */
    public function andNot();

    /**
     * @return self
     */
    public function orNot();

    /**
     * @return self
     */
    public function xorNot();

    /**
     * @return self
     */
    public function startGroup();

    /**
     * @return self
     */
    public function startAndGroup();

    /**
     * @return self
     */
    public function startOrGroup();

    /**
     * @return self
     */
    public function startXorGroup();

    /**
     * @return self
     */
    public function startNotGroup();

    /**
     * @return self
     */
    public function startAndNotGroup();

    /**
     * @return self
     */
    public function startOrNotGroup();

    /**
     * @return self
     */
    public function startXorNotGroup();

    public function __call($method, $args);
}