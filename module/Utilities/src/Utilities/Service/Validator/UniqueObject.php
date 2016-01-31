<?php

namespace Utilities\Service\Validator;

use DoctrineModule\Validator\UniqueObject as DoctrineUniqueObject;

/**
 * UniqueObject Validator
 * 
 * Handles UniqueObject validation business
 * 
 * @package utilities
 * @subpackage validator
 */
class UniqueObject extends DoctrineUniqueObject
{

    /**
     * Returns false if there is another object with the same field values but other identifiers.
     *
     * @access public
     * @param  mixed $value
     * @param  array $context ,default is null
     * @return boolean is valid or not
     */
    public function isValid($value, $context = null)
    {
        if(count($this->fields) > 1){
            $value = $context;
        }
        return parent::isValid($value, $context);
    }

}
