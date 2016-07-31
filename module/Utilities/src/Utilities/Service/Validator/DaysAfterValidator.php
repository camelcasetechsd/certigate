<?php

namespace Utilities\Service\Validator;

use Zend\Validator\AbstractValidator;
use Utilities\Service\Time;

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
class DaysAfterValidator extends AbstractValidator
{

    /**
     * value that stores number pf days needed to be validated against
     * Note : does not meant to store variables like this but only to 
     * point to the other filed validating against but for this case we used it to 
     * pass value to the validator as value not field name 
     * @var int
     */
    protected $token;

    /**
     * error codes
     */
    const MSG_THAN_NUMBER_OF_DAYS = 'lessThan';

    /**
     * Error messages
     * @var array
     */
//    "Required date must not be before %d days of today's date",
    protected $messageTemplates = array(
        self::MSG_THAN_NUMBER_OF_DAYS => "Required date must not be before %value% days of today's date",
    );

    /**
     * Sets validator options
     * 
     * @access public
     * @param mixed $token ,default is null
     */
    public function __construct($token)
    {

        parent::__construct();
        $this->token = $token['diff'];
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
        $dateDiff = date_diff(\DateTime::createFromFormat(Time::DATE_FORMAT, $value), new \DateTime());
        if (($dateDiff->days < $this->token)) {
            $this->error(self::MSG_THAN_NUMBER_OF_DAYS, $this->token);
            $isValid = false;
        }
        return $isValid;
    }

}
