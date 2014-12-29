<?php

namespace PHPixie\Database\Type\Document\Conditions;

interface Builder extends \PHPixie\Database\Conditions\Builder
{
    
    public function addPlaceholder($logic = 'and', $negate = false, $allowEmpty = true);
    public function addSubdocumentPlaceholder($field, $logic = 'and', $negate = false, $allowEmpty = true);
    public function addSubarrayItemPlaceholder($field, $logic = 'and', $negate = false, $allowEmpty = true);
    
    public function startSubdocumentConditionGroup($field, $logic = 'and', $negate = false);
    public function startSubarrayItemConditionGroup($field, $logic = 'and', $negate = false);
    
    public function startSubdocumentGroup($field);
    public function startAndSubdocumentGroup($field);
    public function startOrSubdocumentGroup($field);
    public function startXorSubdocumentGroup($field);
    
    public function startNotSubdocumentGroup($field);
    public function startAndNotSubdocumentGroup($field);
    public function startOrNotSubdocumentGroup($field);
    public function startXorNotSubdocumentGroup($field);
    
    public function startSubarrayItemGroup($field);
    public function startAndSubarrayItemGroup($field);
    public function startOrSubarrayItemGroup($field);
    public function startXorSubarrayItemGroup($field);
    
    public function startNotSubarrayItemGroup($field);
    public function startAndNotSubarrayItemGroup($field);
    public function startOrNotSubarrayItemGroup($field);
    public function startXorNotSubarrayItemGroup($field);
}
