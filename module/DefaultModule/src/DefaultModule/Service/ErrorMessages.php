<?php

namespace DefaultModule\Service;

/**
 * ErrorMessages
 * 
 * Hold error messages
 * 
 * @package defaultModule
 * @subpackage service
 */
class ErrorMessages
{

    const NO_INSTRUCTOR_FOUND = "No training found for instructor!";

    /**
     * Get error message for key
     * 
     * @access public
     * @param string $messageKey
     * @return string message if found
     */
    public static function getErrorMessage($messageKey)
    {
        $message = null;
        $errorMessagesReflection = new \ReflectionClass('DefaultModule\Service\ErrorMessages');
        $errorMessages = $errorMessagesReflection->getConstants();
        if(!empty($messageKey) && array_key_exists($messageKey, $errorMessages)){
            $message = $errorMessages[$messageKey];
        }
        return $message;
    }

}
