<?php

namespace Users\Model;

use Users\Entity\User as UserEntity;
use Utilities\Service\Random;
use Zend\File\Transfer\Adapter\Http;
use Users\Entity\Role;
use Utilities\Service\Status;
use System\Service\Settings;
use Notifications\Service\MailTemplates;
use Notifications\Service\MailSubjects;
use System\Service\Cache\CacheHandler;
use Zend\Authentication\AuthenticationService;
use EStore\Service\ApiCalls;
use Zend\Http\Request;
use Utilities\Service\Paginator\PaginatorAdapter;
use Zend\Paginator\Paginator;

/**
 * User Model
 * 
 * Handles User Entity related business
 * 
 * 
 * 
 * @property Utilities\Service\Query\Query $query
 * @property Utilities\Service\Random $random
 * @property System\Service\Cache\CacheHandler $systemCacheHandler
 * @property Notifications\Service\Notification $notification
 * @property Users\Auth\Authentication $auth
 * @property EStore\Service\Api $estoreApi
 * @property Organizations\Model\OrganizationUser $organizationUserModel
 * 
 * @package users
 * @subpackage model
 */
class User
{

    use \Utilities\Service\Paginator\PaginatorTrait;

    /**
     *
     * @var Utilities\Service\Query\Query 
     */
    protected $query;

    /**
     *
     * @var Utilities\Service\Random 
     */
    protected $random;

    /**
     *
     * @var System\Service\Cache\CacheHandler
     */
    protected $systemCacheHandler;

    /**
     *
     * @var Notifications\Service\Notification
     */
    protected $notification;

    /**
     *
     * @var Users\Auth\Authentication
     */
    protected $auth;

    /**
     *
     * @var EStore\Service\Api
     */
    protected $estoreApi;

    /**
     *
     * @var Organizations\Model\OrganizationUser
     */
    protected $organizationUserModel;

    /**
     * Set needed properties
     * 
     * 
     * @access public
     * @uses Random
     * 
     * @param Utilities\Service\Query\Query $query
     * @param System\Service\Cache\CacheHandler $systemCacheHandler
     * @param Notifications\Service\Notification $notification
     * @param Users\Auth\Authentication $auth
     * @param EStore\Service\Api $estoreApi
     * @param Organizations\Model\OrganizationUser $organizationUserModel
     */
    public function __construct($query, $systemCacheHandler, $notification, $auth, $estoreApi, $organizationUserModel)
    {
        $this->query = $query;
        $this->systemCacheHandler = $systemCacheHandler;
        $this->notification = $notification;
        $this->auth = $auth;
        $this->estoreApi = $estoreApi;
        $this->organizationUserModel = $organizationUserModel;
        $this->random = new Random();
        $this->paginator = new Paginator(new PaginatorAdapter($query, "CMS\Entity\Page"));
    }

    /**
     * Save User
     * 
     * 
     * @access public
     * @uses UserEntity
     * 
     * @param array $userInfo
     * @param UserEntity $userObj ,default is null in case new user is being created
     * @param bool $isAdminUser ,default is true
     * @param bool $editFormFlag ,default is null
     * @param float $oldLongitude ,default is null
     * @param float $oldLatitude ,default is null
     */
    public function saveUser($userInfo, $userObj = null, $isAdminUser = true, $editFormFlag = null, $oldLongitude = null, $oldLatitude = null)
    {
        $sendNotificationFlag = false;
        if (is_null($editFormFlag)) {
            $editFormFlag = true;
        }
        if (is_null($userObj)) {
            $editFormFlag = false;
            $userObj = new UserEntity();
            if ($isAdminUser === false) {
                $sendNotificationFlag = true;
            }
        }
        if (!empty($userInfo['password'])) {
            $userInfo['password'] = UserEntity::hashPassword($userInfo['password']);
        }
        if (!(empty($userInfo['photo']['name']) && $editFormFlag === true)) {
            $userInfo['photo'] = $this->savePhoto($userInfo['photo']);
        }
        $userInfo['status'] = Status::STATUS_ACTIVE;

        // All users should always have user role
        $userRole = $this->query->findOneBy("Users\Entity\Role", /* $criteria = */ array(
            "name" => Role::USER_ROLE));
        if (!isset($userInfo['roles'])) {
            $userInfo['roles'] = array();
        }
        if (!in_array($userRole->getId(), $userInfo['roles'])) {
            $userInfo['roles'][] = $userRole->getId();
        }
        // create/ update customer in estore in case user is newly created or updated
        $this->saveUserCustomer($userObj, $userInfo, $editFormFlag);
        $this->query->setEntity("Users\Entity\User")->save($userObj, $userInfo);

        $userRoles = $userObj->getRolesNames();
        if ($sendNotificationFlag === true) {
            $userEmail = $userObj->getEmail();
            $this->sendMail($userEmail, $userRoles);
        }

        if ($editFormFlag === true && in_array(Role::PROCTOR_ROLE, $userRoles) && ($oldLatitude != $userObj->getLatitude() || $oldLongitude != $userObj->getLongitude() )) {
            $this->organizationUserModel->sortProctors(/* $organizationId = */ null, /* $userId = */ $userObj->getId());
        }

        // update session if current logged in user is the updated one
        $authenticationService = new AuthenticationService();
        $storage = $authenticationService->getIdentity();
        if (!$authenticationService->hasIdentity() || ($authenticationService->hasIdentity() && $storage['id'] == $userObj->getId())) {
            $authenticationService->clearIdentity();
            $this->auth->newSession($userObj);
        }
    }

    /**
     * Save user customer
     * 
     * @access public
     * @param Users\Entity\User $user
     * @param array $data ,default is empty array
     * @param bool $editFlag ,default is bool false
     */
    public function saveUserCustomer($user, $data = array(), $editFlag = false)
    {
        if ($editFlag === true) {
            $estoreApiEdge = ApiCalls::CUSTOMER_EDIT;
            $data = $user->getArrayCopy();
        }
        else {
            $estoreApiEdge = ApiCalls::CUSTOMER_ADD;
        }
        $parameters = array(
            'firstname' => $data["firstName"],
            'lastname' => $data["lastName"],
            'email' => $data["email"],
            'password' => "",
            'telephone' => $data["mobile"],
            'fax' => "",
            'company' => "",
            'address_1' => $data["addressOne"],
            'address_2' => $data["addressTwo"],
            'city' => $data["city"],
            'postcode' => $data["zipCode"],
            'country_iso_code_2' => $data["country"],
            'zone_id' => "",
            'agree' => true,
        );
        $queryParameters = array();
        if (!empty($user->getCustomerId())) {
            $queryParameters["customer_id"] = $user->getCustomerId();
        }
        $responseContent = $this->estoreApi->callEdge(/* $edge = */ $estoreApiEdge, /* $method = */ Request::METHOD_POST, $queryParameters, $parameters);
        if (empty($user->getCustomerId())) {
            $user->setCustomerId($responseContent->customerId);
        }
    }

    /**
     * Save user photo
     * 
     * 
     * @access protected
     * @uses Http
     * 
     * @param array $photoData uploaded file data
     * @return string new attachment file name
     */
    protected function savePhoto($photoData)
    {
        $uploadResult = '/upload/images/userdefault.png';
        if (!empty($photoData['name'])) {
            $upload = new Http();
            $imagesPath = 'public/upload/images/';
            $upload->setDestination($imagesPath);

            try {
                // upload received file(s)
                $upload->receive();
                $uploadResult = null;
            } catch (\Exception $e) {
                // nothing to do in case upload failed, just use default image
            }
        }

        if (!(isset($uploadResult) && !empty($uploadResult))) {
            $name = $upload->getFileName('photo');
            $extention = pathinfo($name, PATHINFO_EXTENSION);
            //get random new name
            $newName = $this->random->getRandomUniqueName();

            rename($name, 'public/upload/images/' . $newName . '.' . $extention);

            $uploadResult = '/upload/images/' . $newName . '.' . $extention;
        }
        return $uploadResult;
    }

    /**
     * Delete User
     * 
     * 
     * @access public
     * @param int $userId
     */
    public function deleteUser($userId)
    {
        $user = $this->query->find(/* $entityName = */ 'Users\Entity\User', $userId);
        $user->status = Status::STATUS_DELETED;
        $this->query->entityManager->merge($user);
        $this->query->entityManager->flush($user);
    }

    /**
     * Activate User
     * 
     * 
     * @access public
     * @param int $userId
     */
    public function ActivateUser($userId)
    {
        $user = $this->query->find(/* $entityName = */ 'Users\Entity\User', $userId);
        $user->status = Status::STATUS_ACTIVE;
        $this->query->entityManager->merge($user);
        $this->query->entityManager->flush($user);
    }

    /**
     * Send mail
     * 
     * @access private
     * @param string $userEmail
     * @param array $userRoles
     * @throws \Exception From email is not set
     * @throws \Exception Admin email is not set
     * @throws \Exception Operations email is not set
     */
    private function sendMail($userEmail, $userRoles)
    {
        $forceFlush = (APPLICATION_ENV == "production" ) ? false : true;
        $cachedSystemData = $this->systemCacheHandler->getCachedSystemData($forceFlush);
        $settings = $cachedSystemData[CacheHandler::SETTINGS_KEY];

        if (array_key_exists(Settings::SYSTEM_EMAIL, $settings)) {
            $from = $settings[Settings::SYSTEM_EMAIL];
        }
        if (array_key_exists(Settings::ADMIN_EMAIL, $settings)) {
            $adminEmail = $settings[Settings::ADMIN_EMAIL];
        }
        if (array_key_exists(Settings::OPERATIONS_EMAIL, $settings)) {
            $operationsEmail = $settings[Settings::OPERATIONS_EMAIL];
        }

        if (!isset($from)) {
            throw new \Exception("From email is not set");
        }
        if (!isset($adminEmail)) {
            throw new \Exception("Admin email is not set");
        }

        $templateParameters = array(
            "email" => $userEmail,
        );

        $notificationMailArray = array(
            'to' => $adminEmail,
            'from' => $from,
            'templateName' => MailTemplates::NEW_USER_NOTIFICATION_TEMPLATE,
            'templateParameters' => $templateParameters,
            'subject' => MailSubjects::NEW_USER_NOTIFICATION_SUBJECT,
        );
        $this->notification->notify($notificationMailArray);

        if (in_array(Role::INSTRUCTOR_ROLE, $userRoles)) {
            if (!isset($operationsEmail)) {
                throw new \Exception("Operations email is not set");
            }
            $welcomeKitMailArray = array(
                'to' => $operationsEmail,
                'from' => $from,
                'templateName' => MailTemplates::NEW_INSTRUCTOR_WELCOME_KIT_TEMPLATE,
                'templateParameters' => $templateParameters,
                'subject' => MailSubjects::NEW_INSTRUCTOR_WELCOME_KIT_SUBJECT,
            );
            $this->notification->notify($welcomeKitMailArray);
        }
    }

    /**
     * Filter instructors
     * 
     * @access public
     * @throws \Exception Instructor Role is not found
     */
    public function filterInstructors()
    {
        $adapter = $this->paginator->getAdapter();
        $roles = array(Role::INSTRUCTOR_ROLE);
        $adapter->setQuery($this->query->setEntity("Users\Entity\User")->entityRepository);
        $adapter->setMethodName("getUsers");
        $adapter->setParameters(array(
            "roles" => $roles,
            "status" => Status::STATUS_ACTIVE,
        ));
    }

    public function addRolesAgreementValidators($data, $form)
    {
        // validating roles agreement 
        $rolesSelected = $data['roles'];

        /* @var $rolesElement DoctrineModule\Form\Element\ObjectMultiCheckbox */
        $rolesValues = $form->get('roles')->getValueOptions();

        foreach ($rolesSelected as $r) {
            $roleLabel = call_user_func(function($r, $rolesValues) {
                foreach ($rolesValues as $rv) {
                    if ($rv['value'] == $r) {
                        return $rv['label'];
                    }
                }
            }, $r, $rolesValues);

            $statementElementName = lcfirst(str_replace([" ", "-"], "", $roleLabel)) . "Statement";
            if ($statementElementName == "reSellerStatement") {
                $statementElementName = "resellerStatement";
            }
            $inputFilter = $form->getInputFilter()->get($statementElementName);
            $inputFilter->setRequired(TRUE);
            $inputFilter->getValidatorChain()->attach(new \Zend\Validator\Identical(array(
                'token'    => (string) \Utilities\Service\Status::STATUS_ACTIVE,
                'messages' => array(
                    \Zend\Validator\Identical::NOT_SAME => 'You must agree to the ' . $roleLabel . ' privacy statement',
            ))));
            $inputFilter->getValidatorChain()->attach(new \Zend\Validator\NotEmpty(array(
                'messages' => array(
                    \Zend\Validator\NotEmpty::IS_EMPTY => 'You must agree to the ' . $roleLabel . ' privacy statement',
            ))));
        }
    }

}
