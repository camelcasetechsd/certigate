<?php

namespace Users\Model;

use Users\Entity\User as UserEntity;
use Utilities\Service\Random;
use Zend\File\Transfer\Adapter\Http;
use Users\Entity\Role;
use Utilities\Service\Status;
use System\Service\Settings;
use Notifications\Service\MailTempates;
use Notifications\Service\MailSubjects;
use System\Service\Cache\CacheHandler;
use Zend\Authentication\AuthenticationService;

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
 * 
 * @package users
 * @subpackage model
 */
class User
{

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
     */
    public function __construct($query, $systemCacheHandler, $notification, $auth)
    {
        $this->query = $query;
        $this->systemCacheHandler = $systemCacheHandler;
        $this->notification = $notification;
        $this->auth = $auth;
        $this->random = new Random();
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
     */
    public function saveUser($userInfo, $userObj = null, $isAdminUser = true)
    {
        $sendNotificationFlag = false;
        if (is_null($userObj)) {
            $userObj = new UserEntity();
            if($isAdminUser === false){
                $sendNotificationFlag = true;
            }
        }
        if (!empty($userInfo['password'])) {
            $userInfo['password'] = UserEntity::hashPassword($userInfo['password']);
        }
        if (!empty($userInfo['photo']['name'])) {
            $userInfo['photo'] = $this->savePhoto();
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

        $this->query->setEntity("Users\Entity\User")->save($userObj, $userInfo);
        
        if($sendNotificationFlag === true){
            $userEmail = $userObj->getEmail();
            $userRoles = $userObj->getRolesNames();
            $this->sendMail($userEmail, $userRoles);
        }
        // update session if current logged in user is the updated one
        $authenticationService = new AuthenticationService();
        $storage = $authenticationService->getIdentity();
        if($authenticationService->hasIdentity() && $storage['id'] == $userObj->getId()){
            $authenticationService->clearIdentity();
            $this->auth->newSession($userObj);
        }
    }

    /**
     * Save user photo
     * 
     * 
     * @access protected
     * @uses Http
     * 
     * @return string new attachment file name
     */
    protected function savePhoto()
    {
        $uploadResult = null;
        $upload = new Http();
        $imagesPath = 'public/upload/images/';
        $upload->setDestination($imagesPath);

        try {
            // upload received file(s)
            $upload->receive();
        } catch (\Exception $e) {
            $uploadResult = '/upload/images/defaultpic.png';
        }

        $name = $upload->getFileName('photo');
        $extention = pathinfo($name, PATHINFO_EXTENSION);
        //get random new name
        $newName = $this->random->getRandomUniqueName();

        rename($name, 'public/upload/images/' . $newName . '.' . $extention);

        $uploadResult = '/upload/images/' . $newName . '.' . $extention;
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
            'templateName' => MailTempates::NEW_USER_NOTIFICATION_TEMPLATE,
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
                'templateName' => MailTempates::NEW_INSTRUCTOR_WELCOME_KIT_TEMPLATE,
                'templateParameters' => $templateParameters,
                'subject' => MailSubjects::NEW_INSTRUCTOR_WELCOME_KIT_SUBJECT,
            );
            $this->notification->notify($welcomeKitMailArray);
        }
    }

}
