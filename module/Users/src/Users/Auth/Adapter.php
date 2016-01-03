<?php

namespace Users\Auth;

use Zend\Authentication\Adapter\AdapterInterface;
use Users\Entity\User;
use Zend\Authentication\Adapter\Exception\RuntimeException;
use Zend\Authentication\Result;

/**
 * Auth Adapter
 * 
 * Prepare Authentication needed business
 * 
 * 
 * @property Utilities\Service\Query\Query $_query ,default is null
 * @property string $_identityColumn ,default is null
 * @property string $_credentialColumn ,default is null
 * @property string $_identity ,default is null
 * @property string $_credential ,default is null
 * @property array $_authenticateResultInfo ,default is null
 * 
 * @package users
 * @subpackage auth
 */
class Adapter implements AdapterInterface
{

    /**
     * @var Utilities\Service\Query\Query
     */
    protected $_query = null;

    /**
     * $_identityColumn - the column to use as the identity
     *
     * @var string
     */
    protected $_identityColumn = null;

    /**
     * $_credentialColumn - columns to be used as the credentials
     *
     * @var string
     */
    protected $_credentialColumn = null;

    /**
     * $_identity - Identity value
     *
     * @var string
     */
    protected $_identity = null;

    /**
     * $_credential - Credential values
     *
     * @var string
     */
    protected $_credential = null;

    /**
     * $_authenticateResultInfo
     *
     * @var array
     */
    protected $_authenticateResultInfo = null;

    /**
     * Set configuration options
     * 
     * 
     * @access public
     * @param Utilities\Service\Query\Query $query ,default is null
     * @param string $identityColumn ,default is null
     * @param string $credentialColumn ,default is null
     */
    public function __construct($query = null, $identityColumn = null, $credentialColumn = null)
    {
        if (null !== $query) {
            $this->setQuery($query);
        }

        if (null !== $identityColumn) {
            $this->setIdentityColumn($identityColumn);
        }

        if (null !== $credentialColumn) {
            $this->setCredentialColumn($credentialColumn);
        }
    }

    /**
     * Set the Query
     * 
     * 
     * @access public
     * @param Utilities\Service\Query\Query $query
     */
    public function setQuery($query)
    {
        $this->_query = $query;
        return $this;
    }

    /**
     * Set the column name to be used as the identity column
     * 
     * 
     * @access public
     * @param  string $identityColumn
     * @return Adapter Provides a fluent interface
     */
    public function setIdentityColumn($identityColumn)
    {
        $this->_identityColumn = $identityColumn;
        return $this;
    }

    /**
     * Set the column name to be used as the credential column
     * 
     * 
     * @access public
     * @param  string $credentialColumn
     * @return Adapter Provides a fluent interface
     */
    public function setCredentialColumn($credentialColumn)
    {
        $this->_credentialColumn = $credentialColumn;
        return $this;
    }

    /**
     * Set the value to be used as the identity
     * 
     * 
     * @access public
     * @param  string $value
     * @return Adapter Provides a fluent interface
     */
    public function setIdentity($value)
    {
        $this->_identity = $value;
        return $this;
    }

    /**
     * Set the credential value to be used
     * 
     * 
     * @access public
     * @param  string $credential
     * @return Adapter Provides a fluent interface
     */
    public function setCredential($credential)
    {
        $this->_credential = $credential;
        return $this;
    }

    /**
     * authenticate() - defined by AdapterInterface.  
     * Attempt an authentication.  
     * Previous to this call, this adapter would have already been configured 
     * with all necessary information to successfully connect to a database table 
     * and attempt to find a record matching the provided identity.
     * 
     * 
     * @access public
     * @throws RuntimeException if answering the authentication query is impossible
     * @return Result
     */
    public function authenticate()
    {
        $this->authenticateSetup();
        $entities = $this->_query->findBy(/*$userName = */'Users\Entity\User',array(
            'username' => $this->_identity,
        ));
        
        return $this->validateResult($entities);
    }

    /**
     * Abstract the steps involved with making sure that this adapter was indeed setup properly 
     * with all required pieces of information.
     * 
     * 
     * @access protected
     * @throws RuntimeException - in the event that setup was not done properly
     * @return true
     */
    protected function authenticateSetup()
    {
        $exception = null;

        if ($this->_query === null) {
            $exception = 'A database connection was not set, nor could one be created.';
        } elseif ($this->_identityColumn == '') {
            $exception = 'An identity column must be supplied for the Adapter authentication adapter.';
        } elseif ($this->_credentialColumn == '') {
            $exception = 'A credential column must be supplied for the Adapter authentication adapter.';
        } elseif ($this->_identity == '') {
            $exception = 'A value for the identity was not provided prior to authentication with Adapter.';
        } elseif ($this->_credential === null) {
            $exception = 'A credential value was not provided prior to authentication with Adapter.';
        }

        if (null !== $exception) {
            /**
             * @see RuntimeException
             */
            throw new RuntimeException($exception);
        }

        $this->_authenticateResultInfo = array(
            'code' => Result::FAILURE,
            'identity' => $this->_identity,
            'messages' => array()
        );

        return true;
    }

    /**
     * Validate that the record in the result set is indeed a record 
     * that matched the identity provided to this adapter.
     * 
     * 
     * @access protected
     * @param array $resultIdentities
     * @return Result
     */
    protected function validateResult($resultIdentities)
    {        
        if (count($resultIdentities) < 1) {
            $this->_authenticateResultInfo['code'] = Result::FAILURE_IDENTITY_NOT_FOUND;
            $this->_authenticateResultInfo['messages'][] = 'A record with the supplied identity could not be found.';
            return $this->authenticateCreateAuthResult();
        } elseif (count($resultIdentities) > 1) {
            $this->_authenticateResultInfo['code'] = Result::FAILURE_IDENTITY_AMBIGUOUS;
            $this->_authenticateResultInfo['messages'][] = 'More than one record matches the supplied identity.';
            return $this->authenticateCreateAuthResult();
        } elseif (count($resultIdentities) == 1) {
            $resultIdentity = $resultIdentities[0];
            $password = $resultIdentity->{$this->_credentialColumn};
            
            if (! User::verifyPassword($this->_credential, $password)) {
                $this->_authenticateResultInfo['code'] = Result::FAILURE_CREDENTIAL_INVALID;
                $this->_authenticateResultInfo['messages'][] = 'Supplied credential is invalid.';
            } else {
                $this->_authenticateResultInfo['code'] = Result::SUCCESS;
                $this->_authenticateResultInfo['identity'] = $this->_identity;
                $this->_authenticateResultInfo['messages'][] = 'Authentication successful.';
            }
        } else {
            $this->_authenticateResultInfo['code'] = Result::FAILURE_UNCATEGORIZED;
        }

        return $this->authenticateCreateAuthResult();
    }

    /**
     * Create a Result object 
     * from the information that has been collected during the authenticate() attempt.
     * 
     * 
     * @access protected
     * @uses Result
     * 
     * @return Result
     */
    protected function authenticateCreateAuthResult()
    {
        return new Result(
                $this->_authenticateResultInfo['code'], $this->_authenticateResultInfo['identity'], $this->_authenticateResultInfo['messages']
        );
    }

}
