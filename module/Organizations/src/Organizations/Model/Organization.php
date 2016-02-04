<?php

namespace Organizations\Model;

use Utilities\Service\Random;
use Zend\File\Transfer\Adapter\Http;
use Utilities\Service\Status;
use Users\Entity\Role;
use Organizations\Entity\Organization as OrganizationEntity;
use Utilities\Service\Time;
use System\Service\Settings;
use Notifications\Service\MailTempates;
use Notifications\Service\MailSubjects;
use System\Service\Cache\CacheHandler;

/**
 * Org Model
 * 
 * Handles Org Entity related business
 * 
 * 
 * 
 * @property Utilities\Service\Query\Query $query
 * @property Utilities\Service\Random $random
 * @property System\Service\Cache\CacheHandler $systemCacheHandler
 * @property Notifications\Service\Notification $notification
 * 
 * @package organizations
 * @subpackage model
 */
class Organization
{

    protected $CR_ATTACHMENT_PATH = 'public/upload/attachments/crAttachments/';
    protected $WIRE_ATTACHMENT_PATH = 'public/upload/attachments/wireAttachments/';
    protected $ATP_ATTACHMENT_PATH = 'public/upload/attachments/atpAttachments/';
    protected $ATC_ATTACHMENT_PATH = 'public/upload/attachments/atcAttachments/';
    /*
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
     * Set needed properties
     * 
     * 
     * @access public
     * @uses Random
     * 
     * @param Utilities\Service\Query\Query $query
     * @param System\Service\Cache\CacheHandler $systemCacheHandler
     * @param Notifications\Service\Notification $notification
     */
    public function __construct($query, $systemCacheHandler, $notification)
    {
        $this->query = $query;
        $this->systemCacheHandler = $systemCacheHandler;
        $this->notification = $notification;
        $this->random = new Random();
    }

    public function getUsers()
    {
        return $this->query->findAll(/* $entityName = */ 'Users\Entity\User');
    }

    public function getUserby($targetColumn, $value)
    {
        return $this->query->findBy(/* $entityName = */ 'Users\Entity\User', array(
                    $targetColumn => $value
        ));
    }

    public function checkOrgExistance($commericalName)
    {
        return $this->getOrganizationby('commercialName', $commericalName);
    }

    public function getOrganizations()
    {
        return $this->query->findAll(/* $entityName = */ 'Organizations\Entity\Organization');
    }

    public function getOrganizationby($targetColumn, $value)
    {
        return $this->query->findBy(/* $entityName = */ 'Organizations\Entity\Organization', array(
                    $targetColumn => $value
        ));
    }

    /**
     * this function is meant to list organizations by type
     * its alrady list organizations with type 3 in the same time with
     * type wanted to be listed  for example if we wanted to list atps it
     * will post both atps and organizations which is both atp and atc 
     * at the same time
     * 
     * @param type $query
     * @param type $type
     * @return type
     */
    public function listOrganizations($query, $type)
    {
        $em = $query->entityManager;
        $dqlQuery = $em->createQuery('SELECT u FROM Organizations\Entity\Organization u WHERE u.active = 2 and (u.type =?1 or u.type = 3)');
        $dqlQuery->setParameter(1, $type);
        return $dqlQuery->getResult();
    }

    /**
     * Save organization
     * 
     * @access public
     * @param array $orgInfo
     * @param Organizations\Entity\Organization $orgObj ,default is null
     * @param int $creatorId ,default is null
     * @param string $userEmail ,default is null
     * @param bool $isAdminUser ,default is true
     */
    public function saveOrganization($orgInfo, $orgObj = null, $creatorId = null, $userEmail = null, $isAdminUser = true)
    {

        $roles = $this->query->findAll('Users\Entity\Role');
        $rolesIds = array();
        foreach ($roles as $role) {
            $rolesIds[$role->getName()] = $role->getId();
        }

        $sendNotificationFlag = false;
        if (is_null($orgObj)) {

            $entity = new \Organizations\Entity\Organization();
            if ($isAdminUser === false) {
                $sendNotificationFlag = true;
            }
        }
        else {
            $entity = $orgObj;
        }

//       
        /**
         * Handling convert string date to datetime object
         */
        if (!empty($orgInfo['CRExpiration'])) {
            $date = \DateTime::createFromFormat(Time::DATE_FORMAT, $orgInfo['CRExpiration']);
            $orgInfo['CRExpiration'] = $date;
        }

        if (!empty($orgInfo['atcLicenseExpiration']) && $orgInfo['atcLicenseExpiration'] != "") {
            $date = \DateTime::createFromFormat(Time::DATE_FORMAT, $orgInfo['atcLicenseExpiration']);
            $orgInfo['atcLicenseExpiration'] = $date;
        }
        else {
            $orgInfo['atcLicenseExpiration'] = null;
        }
        if (!empty($orgInfo['atpLicenseExpiration']) && $orgInfo['atpLicenseExpiration'] != "") {
            $date = \DateTime::createFromFormat(Time::DATE_FORMAT, $orgInfo['atpLicenseExpiration']);
            $orgInfo['atpLicenseExpiration'] = $date;
        }
        else {
            $orgInfo['atpLicenseExpiration'] = null;
        }

        /**
         * Handling User Forign keys
         */
        // training manager can be null if not selected 
        // test admin can be null if not selected 
        // focal can be null
        if (!empty($orgInfo['focalContactPerson_id']) && $orgInfo['focalContactPerson_id'] != 0) {
            $orgInfo['focalContactPerson_id'] = $this->getUserby('id', $orgInfo['focalContactPerson_id'])[0];
        }

        /**
         * Handling transfered Files
         */
        /**
         * Handling transfered Files
         */
        if (!empty($orgInfo['CRAttachment']['name'])) {
            $orgInfo['CRAttachment'] = $this->saveAttachment('CRAttachment', 'cr');
        }
        if (!empty($orgInfo['wireTransferAttachment']['name'])) {
            $orgInfo['wireTransferAttachment'] = $this->saveAttachment('wireTransferAttachment', 'wr');
        }
        if (!empty($orgInfo['atpLicenseAttachment']['name'])) {
            $orgInfo['atpLicenseAttachment'] = $this->saveAttachment('atpLicenseAttachment', 'atp');
        }
        if (!empty($orgInfo['atcLicenseAttachment']['name'])) {
            $orgInfo['atcLicenseAttachment'] = $this->saveAttachment('atcLicenseAttachment', 'atc');
        }

        /**
         * Save Organization
         */
        $this->query->setEntity('Organizations\Entity\Organization')->save($entity, $orgInfo);
        // if there's 
        if (!empty($orgInfo['trainingManager_id']) && $orgInfo['trainingManager_id'] != 0) {
            $this->assignUserToOrg($entity, $orgInfo['trainingManager_id'], $rolesIds[Role::TRAINING_MANAGER_ROLE]);
            $this->assignUserToOrg($entity, $creatorId, $rolesIds[Role::TRAINING_MANAGER_ROLE]);
        }
        else if ($orgInfo['trainingManager_id'] != 0) {
            $this->assignUserToOrg($entity, $creatorId, $rolesIds[Role::TRAINING_MANAGER_ROLE]);
        }
        if (!empty($orgInfo['testCenterAdmin_id']) && $orgInfo['testCenterAdmin_id'] != 0) {
            $this->assignUserToOrg($entity, $orgInfo['testCenterAdmin_id'], $rolesIds[Role::TEST_CENTER_ADMIN_ROLE]);
            $this->assignUserToOrg($entity, $creatorId, $rolesIds[Role::TEST_CENTER_ADMIN_ROLE]);
        }
        else if ($orgInfo['testCenterAdmin_id'] != 0) {
            $this->assignUserToOrg($entity, $creatorId, $rolesIds[Role::TEST_CENTER_ADMIN_ROLE]);
        }

        if ($sendNotificationFlag === true) {
            $this->sendMail($userEmail);
        }
    }

    private function saveAttachment($filename, $type)
    {
        switch ($type) {
            case 'cr':
                $uploadResult = $this->uploadAttachment($filename, $this->CR_ATTACHMENT_PATH);
                break;
            case 'wr':
                $uploadResult = $this->uploadAttachment($filename, $this->WIRE_ATTACHMENT_PATH);
                break;
            case 'atp':
                $uploadResult = $this->uploadAttachment($filename, $this->ATP_ATTACHMENT_PATH);
                break;
            case 'atc':
                $uploadResult = $this->uploadAttachment($filename, $this->ATC_ATTACHMENT_PATH);
                break;
        }
        return $uploadResult;
    }

    private function uploadAttachment($filename, $attachmentPath)
    {
        $uploadResult = null;
        $upload = new Http();
        $upload->setDestination($attachmentPath);
        try {
// upload received file(s)
            $upload->receive($filename);
        } catch (\Exception $e) {
            return $uploadResult;
        }
//This method will return the real file name of a transferred file.
        $name = $upload->getFileName($filename);
//This method will return extension of the transferred file
        $extention = pathinfo($name, PATHINFO_EXTENSION);
//get random new name
        $newName = $this->random->getRandomUniqueName() . '_' . date('Y.m.d_h:i:sa');
        $newFullName = $attachmentPath . $newName . '.' . $extention;
// rename
        rename($name, $newFullName);
        $uploadResult = $newFullName;
        return $uploadResult;
    }

    /**
     * Delete orhanization
     * 
     * 
     * @access public
     * @param int $id
     */
    public function deleteOrganization($id)
    {
        $org = $this->query->find(/* $entityName = */ 'Organizations\Entity\Organization', $id);
        $org->active = \Organizations\Entity\Organization::NOT_ACTIVE;
        $this->query->entityManager->merge($org);
        $this->query->entityManager->flush($org);
    }

    public function prepareStatics($variables)
    {

        $staticOs = \Organizations\Entity\Organization::getOSs();
        $staticLangs = \Organizations\Entity\Organization::getStaticLangs();
        $staticVersions = \Organizations\Entity\Organization::getOfficeVersions();

        if (isset($variables['userData']->operatingSystem)) {
            $variables['userData']->operatingSystem = $staticOs[$variables['userData']->operatingSystem];
        }
        if (isset($variables['userData']->operatingSystemLang)) {
            $variables['userData']->operatingSystemLang = $staticLangs[$variables['userData']->operatingSystemLang];
        }
        if (isset($variables['userData']->officeLang)) {
            $variables['userData']->officeLang = $staticLangs[$variables['userData']->officeLang];
        }
        if (isset($variables['userData']->officeVersion)) {
            $variables['userData']->officeVersion = $staticVersions[$variables['userData']->officeVersion];
        }

        return $variables;
    }

    /**
     * this function meant to assign an user to an organization with specific type
     *  
     * @param Organization $orgObj
     * @param int $userId
     * @param int $roleId
     */
    private function assignUserToOrg($orgObj, $userId, $roleId)
    {
        $orgUserObj = new \Organizations\Entity\OrganizationUser();
        $orgUserObj->setOrganizationUser($orgObj, $this->query->findOneBy('Users\Entity\User', array(
                    'id' => $userId
        )));
        $role = $this->query->find('Users\Entity\Role', $roleId);
        $orgUserObj->setRole($role);
        $this->query->setEntity('Organizations\Entity\OrganizationUser')->save($orgUserObj);
    }

    /**
     * prepare organizations for display
     * 
     * 
     * @access public
     * @param array $organizationsArray
     * @return array organizations prepared for display
     */
    public function prepareForDisplay(array $organizationsArray)
    {
        foreach ($organizationsArray as $organization) {

            switch ($organization->type) {
                case OrganizationEntity::TYPE_ATC:
                    $organization->typeText = "ATC";
                    break;
                case OrganizationEntity::TYPE_ATP:
                    $organization->typeText = "ATP";
                    break;
                case OrganizationEntity::TYPE_BOTH:
                    $organization->typeText = "ATC/ATP";
                    break;
            }
            switch ($organization->active) {
                case OrganizationEntity::ACTIVE:
                    $organization->activeText = Status::STATUS_ACTIVE_TEXT;
                    break;
                case OrganizationEntity::NOT_APPROVED:
                    $organization->activeText = Status::STATUS_NOT_APPROVED_TEXT;
                    break;
                case OrganizationEntity::NOT_ACTIVE:
                    $organization->activeText = Status::STATUS_INACTIVE_TEXT;
                    break;
            }
        }
        return $organizationsArray;
    }

    /**
     * Send mail
     * 
     * @access private
     * @param string $userEmail
     * @throws \Exception From email is not set
     * @throws \Exception Admin email is not set
     * @throws \Exception Operations email is not set
     */
    private function sendMail($userEmail)
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
        if (!isset($operationsEmail)) {
            throw new \Exception("Operations email is not set");
        }
        $templateParameters = array(
            "email" => $userEmail,
        );
        $notificationMailArray = array(
            'to' => $adminEmail,
            'from' => $from,
            'templateName' => MailTempates::NEW_ORGANIZATION_NOTIFICATION_TEMPLATE,
            'templateParameters' => $templateParameters,
            'subject' => MailSubjects::NEW_ORGANIZATION_NOTIFICATION_SUBJECT,
        );
        $this->notification->notify($notificationMailArray);

        $welcomeKitMailArray = array(
            'to' => $operationsEmail,
            'from' => $from,
            'templateName' => MailTempates::NEW_ORGANIZATION_WELCOME_KIT_TEMPLATE,
            'templateParameters' => $templateParameters,
            'subject' => MailSubjects::NEW_ORGANIZATION_WELCOME_KIT_SUBJECT,
        );
        $this->notification->notify($welcomeKitMailArray);
    }

}
