<?php

namespace Users\Auth;

use Utilities\Service\Query\Query;
use Users\Auth\Adapter;
use Zend\Authentication\AuthenticationService;
use EStore\Service\ApiCalls;
use Zend\Http\Request;

/**
 * Authentication
 *
 * Handles Authentication related business
 *
 * 
 * @property Zend\Http\Request $request
 * @property Utilities\Service\Query\Query $query
 * @property EStore\Service\Api $estoreApi
 * 
 * @package users
 * @subpackage auth
 */
class Authentication
{

    /**
     *
     * @var Zend\Http\Request 
     */
    private $request;

    /**
     *
     * @var Utilities\Service\Query\Query 
     */
    private $query;

    /**
     *
     * @var EStore\Service\Api 
     */
    private $estoreApi;

    /**
     * Set needed properties
     * 
     * 
     * @access public
     * @param Query $query
     * @param EStore\Service\Api $estoreApi
     */
    public function __construct(Query $query, $estoreApi)
    {
        $this->query = $query;
        $this->estoreApi = $estoreApi;
    }

    /**
     * Set request
     * 
     * 
     * @access public
     * @param Zend\Http\Request  $request
     * @return \Users\Auth\Authentication current Authentication service
     */
    public function setRequest($request)
    {
        $this->request = $request;
        return $this;
    }

    /**
     * Attempt authenticating with submitted data
     * 
     * 
     * @access public
     * @uses Adapter
     * 
     * @return Zend\Authentication\Result
     */
    public function authenticateMe()
    {
        //get value of username from post
        $username = $this->request->getPost('username');
        // get value of password from post
        $password = $this->request->getPost('password');
        // hashing password to compare
        $adapter = new Adapter($this->query, "username", "password");
        $adapter->setIdentity($username);
        $adapter->setCredential($password);
        $result = $adapter->authenticate();
        // check on result there's any problem in login
        // if not check on auth plugin
        return $result;
    }

    /**
     * Set session with authenticated user data
     * 
     * 
     * @access public
     * @param Users/Entity/User $user ,default is null
     * @uses AuthenticationService
     */
    public function newSession($user = null)
    {
        if (is_null($user)) {
            $user = $this->query->findOneBy(/* $entityName = */ 'Users\Entity\User', array(
                'username' => $this->request->getPost('username'),
            ));
        }
        $auth = new AuthenticationService();
        $storage = $auth->getStorage();
        // here to add new entries to the session
        $storage->write(array(
            'id' => $user->id,
            'firstName' => $user->getFirstName(),
            'middleName' => $user->getMiddleName(),
            'lastName' => $user->getLastName(),
            'name' => $user->getFullName(),
            'username' => $user->getUsername(),
            'email' => $user->getEmail(),
            'mobile' => $user->getMobile(),
            'photo' => $user->getPhoto(),
            'status' => $user->getStatus(),
            'roles' => $user->getRolesNames(),
            'agreements' => $user->getRolesAgreementsStatus()
        ));
    }
    
    /**
     * Clear authenticated session data
     * 
     * 
     * @access public
     * @uses AuthenticationService
     */
    public function clearSession()
    {
        $auth = new AuthenticationService();
        // clear user-related data in session
        $auth->clearIdentity();
    }

}
