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
 * @author Mohamed Labib <mohamed.labib@camelcasetech.com>
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
     * @author Mohamed Labib <mohamed.labib@camelcasetech.com>
     * 
     * @access public
     * @param Query $query
     */
    public function __construct(Query $query) {
        $this->query = $query;
    }

    /**
     * Set request
     * @author Mohamed Labib <mohamed.labib@camelcasetech.com>
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
     * @author Mohamed Labib <mohamed.labib@camelcasetech.com>
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
     * @author Mohamed Labib <mohamed.labib@camelcasetech.com>
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
        $role = $entity->role;
        // here to add new entries to the session
        $storage->write(array(
            'id' => $entity->id,
            'name' => $entity->name,
            'username' => $entity->username,
            'photo' => $entity->photo,
            'role' => $role,
            'rolename' => (!is_null($role) ) ? $role->name : null,
            'status' => $entity->status
        ));
    }

}
