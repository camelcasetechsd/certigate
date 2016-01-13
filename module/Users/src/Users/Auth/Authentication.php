<?php

namespace Users\Auth;

use Utilities\Service\Query\Query;
use Users\Auth\Adapter;
use Zend\Authentication\AuthenticationService;

/**
 * Authentication
 *
 * Handles Authentication related business
 *
 * 
 * @property Zend\Http\Request $request
 * @property Utilities\Service\Query\Query $query
 * 
 * @package users
 * @subpackage auth
 */
class Authentication {

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
     * Set needed properties
     * 
     * 
     * @access public
     * @param Query $query
     */
    public function __construct(Query $query) {
        $this->query = $query;
    }

    /**
     * Set request
     * 
     * 
     * @access public
     * @param Zend\Http\Request  $request
     * @return \Users\Auth\Authentication current Authentication service
     */
    public function setRequest($request) {
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
    public function authenticateMe() {
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
     * @uses AuthenticationService
     */
    public function newSession() {
        $entity = $this->query->findOneBy(/* $entityName = */ 'Users\Entity\User', array(
            'username' => $this->request->getPost('username'),
        ));
        $auth = new AuthenticationService();
        $storage = $auth->getStorage();
        
        $rolesNames = array();
        foreach($entity->roles as $role){
            $rolesNames[] = $role->getName();
        }
        
        // here to add new entries to the session
        $storage->write(array(
            'id' => $entity->id,
            'firstName' => $entity->firstName,
            'middleName' => $entity->middleName,
            'lastName' => $entity->lastName,
            'name' => $entity->getFullName(),
            'username' => $entity->username,
            'photo' => $entity->photo,
            'status' => $entity->status,
            'roles' => $rolesNames
        ));
    }

}
