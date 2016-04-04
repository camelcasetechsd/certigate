<?php

namespace Users\Auth;

use Zend\Authentication\Result;

/**
 * AuthenticationResult
 *
 * Handles Authentication process result related business
 *
 * 
 * @package users
 * @subpackage auth
 */
class AuthenticationResult extends Result
{
    /**
     * Failure due to not active status
     */
    const FAILURE_NOT_ACTIVE_STATUS = -5;
}
