<?php

namespace Utilities\Service\Validator;

use Zend\Validator\AbstractValidator;

/**
 * Date Validator
 * 
 * 
 * @property array $messageTemplates Error messages
 * @property array $messageVariables
 * @property bool $strict ,default is bool true
 * 
 * @package utilities
 * @subpackage validator
 */
class TenDaysAfterValidator extends AbstractValidator
{

    /**
     * error codes
     */
    const MSG_THAN_TEN_DAYS = 'lessThan';

    /**
     * Error messages
     * @var array
     */
    protected $messageTemplates = array(
        self::MSG_THAN_TEN_DAYS => "Required date must not be before 10 days of today's date",
    );

    /**
     * Sets validator options
     * 
     * @access public
     * @param mixed $token ,default is null
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Returns true if and only if a token has been set and the provided value
     * is greater than that token's.
     * 
     * @access public
     * @param  mixed $value
     * @param  array $context
     * @return boolean valid or not
     */
    public function isValid($value, $context = null)
    {
        $this->setValue($value);
        $isValid = true;
        $dateDiff = date_diff(date_create($value), new \DateTime());
        if (($dateDiff->days < 10)) {
            $this->error(self::MSG_THAN_TEN_DAYS);
            $isValid = false;
        }
        return $isValid;
    }

}
