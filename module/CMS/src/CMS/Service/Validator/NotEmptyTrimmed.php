<?php

namespace CMS\Service\Validator;

use Zend\Validator\AbstractValidator;

/**
 * NotEmptyTrimmed Validator
 * 
 * Handles required validation business for trimmed value 'HTML'
 * 
 * @property array $messageTemplates
 * 
 * @package cms
 * @subpackage validator
 */
class NotEmptyTrimmed extends AbstractValidator
{
    /**
     * is empty error
     */
    const IS_EMPTY = 'isEmpty';

    /**
     *
     * @var array 
     */
    protected $messageTemplates = array(
        self::IS_EMPTY => "Value is required and can't be empty"
    );

    /**
     * Validate input value
     * 
     * @access public
     * @param string $value
     * @return boolean is valid or not
     */
    public function isValid($value)
    {
        $isValid = true;
        $this->setValue($value);

        $trimmedValue = trim(str_replace("&nbsp;", "", preg_replace('/(<p\b[^>]*>|<\/p>)/i', '', $value)));
        if (empty($trimmedValue)) {
            $this->error(self::IS_EMPTY);
            $isValid = false;
        }

        return $isValid;
    }
}