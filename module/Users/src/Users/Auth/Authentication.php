<?php

namespace Users\Auth;

use Utilities\Service\Query\Query;
use Users\Auth\Adapter;
use Zend\Authentication\AuthenticationService;
use CMS\Entity\Menu;
use CMS\Service\Cache\CacheHandler;

/**
 * Authentication
 *
 * Handles Authentication related business
 *
 * 
 * @property Zend\Http\Request $request
 * @property Utilities\Service\Query\Query $query
 * @property EStore\Service\Api $estoreApi
 * @property CMS\Model\MenuItem $menuItem
 * @property CMS\Service\CacheHandler $cacheHandler
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
     *
     * @var CMS\Model\MenuItem
     */
    private $menuItem;
    
    /**
     * @var CMS\Service\CacheHandler
     */
    private $cacheHandler;
    
    /**
     * Set needed properties
     * 
     * 
     * @access public
     * @param Query $query
     * @param EStore\Service\Api $estoreApi
     * @param CMS\Model\MenuItem $menuItem
     * @param CMS\Service\CacheHandler $cacheHandler
     */
    public function __construct(Query $query, $estoreApi, $menuItem, $cacheHandler)
    {
        $this->query = $query;
        $this->estoreApi = $estoreApi;
        $this->menuItem = $menuItem;
        $this->cacheHandler = $cacheHandler;
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
        $forceFlush = (APPLICATION_ENV == "production" )? false : true;;
        $menusArray = $this->cacheHandler->getCachedCMSData($forceFlush);
        $sortedMenuItems = $menusArray[CacheHandler::MENUS_KEY];
        // here to add new entries to the session
        $storage->write(array(
            'id' => $user->id,
            'firstName' => $user->getFirstName(),
            'middleName' => $user->getMiddleName(),
            'lastName' => $user->getLastName(),
            'name' => $user->getFullName(),
            'firstNameAr' => $user->getFirstNameAr(),
            'middleNameAr' => $user->getMiddleNameAr(),
            'lastNameAr' => $user->getLastNameAr(),
            'nameAr' => $user->getFullNameAr(),
            'username' => $user->getUsername(),
            'email' => $user->getEmail(),
            'mobile' => $user->getMobile(),
            'photo' => $user->getPhoto(),
            'status' => $user->getStatus(),
            'roles' => $user->getRolesNames(),
            'agreements' => $user->getRolesAgreementsStatus(),
            'customerId' => $user->getCustomerId(),
            Menu::MANAGE_MENU_UNDERSCORED => $this->menuItem->getManageMenuItems($sortedMenuItems, /*$roles =*/ $user->getRolesNames(), /*$userName =*/ $user->getUsername())
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
