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
use Organizations\Form\OrgForm as OrgForm;

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
 * @property Versioning\Model\Version $version
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
     *
     * @var Versioning\Model\Version
     */
    protected $version;
    /*
     * saves number of organizations in this app
     */
    protected $organizationTypesNumber;

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
     * @param Versioning\Model\Version $version
     */
    public function __construct($query, $systemCacheHandler, $notification, $version)
    {
        $this->query = $query;
        $this->systemCacheHandler = $systemCacheHandler;
        $this->notification = $notification;
        $this->version = $version;
        $this->random = new Random();
        $this->organizationTypesNumber = count($this->getOrganizationTypesNumber());
    }

    public function getOrganizationTypesNumber()
    {
        return $this->query->findAll(/* $entityName = */'Organizations\Entity\OrganizationType');
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

    public function getRegionby($targetColumn, $value)
    {
        return $this->query->findOneBy(/* $entityName = */ 'Organizations\Entity\OrganizationRegion', array(
                    $targetColumn => $value
        ));
    }

    public function getGovernorateby($targetColumn, $value)
    {
        return $this->query->findOneBy(/* $entityName = */ 'Organizations\Entity\OrganizationGovernorate', array(
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
     * its alrady list organizations with type both in the same time with
     * type wanted to be listed  for example if we wanted to list atps it
     * will post both atps and organizations which is both atp and atc 
     * at the same time
     * 
     * @param int $type
     * @return array organizations
     */
    public function listOrganizations($type)
    {
        return $this->query->setEntity("Organizations\Entity\Organization")->entityRepository->listOrganizations($type);
    }

    /**
     * Save organization
     * 
     * @access public
     * @param type $action
     * @param array $orgInfo
     * @param Organizations\Entity\Organization $orgObj ,default is null
     * @param int $oldStatus ,default is null
     * @param int $creatorId ,default is null
     * @param string $userEmail ,default is null
     * @param bool $isAdminUser ,default is true
     * @param bool $saveState ,default is false
     */
    public function saveOrganization($action, $orgInfo, $orgObj = null, $oldStatus = null, $creatorId = null, $userEmail = null, $isAdminUser = true, $saveState = false)
    {
        $editFlag = false;
        $roles = $this->query->findAll('Users\Entity\Role');
        $rolesIds = array();
        foreach ($roles as $role) {
            $rolesIds[$role->getName()] = $role->getId();
        }
        $sendNotificationFlag = false;
        // at create
        if (is_null($orgObj)) {
            $entity = new \Organizations\Entity\Organization();
        }
        // at edit
        else {
            $editFlag = true;
            $entity = $orgObj;
        }
        if ($isAdminUser === false) {
            $sendNotificationFlag = true;
            $entity->setStatus(Status::STATUS_NOT_APPROVED);
        }

//       
        /**
         * Handling convert string date to datetime object
         */
        if (!empty($orgInfo['CRExpiration'])) {
            $date = \DateTime::createFromFormat(Time::DATE_FORMAT, $orgInfo['CRExpiration']);
            $orgInfo['CRExpiration'] = $date;
        }

        if (!empty($orgInfo['atcLicenseExpiration']) && $orgInfo['atcLicenseExpiration'] != "" && !$editFlag) {
            $date = \DateTime::createFromFormat(Time::DATE_FORMAT, $orgInfo['atcLicenseExpiration']);
            $orgInfo['atcLicenseExpiration'] = $date;
        }
        else {
            $orgInfo['atcLicenseExpiration'] = null;
        }
        if (!empty($orgInfo['atpLicenseExpiration']) && $orgInfo['atpLicenseExpiration'] != "" && !$editFlag) {
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

        if (!empty($orgInfo['region']) && $orgInfo['region'] != 0) {
            $regions = $orgInfo['region'];
            $temp = array();
            foreach ($regions as $region) {
                array_push($temp, $this->getRegionby('id', $region));
            }
            $orgInfo['region'] = $temp;

//            $entity->setRegions($temp);
        }

        if (!empty($orgInfo['governorate']) && $orgInfo['governorate'] != 0) {
            $governorates = $orgInfo['governorate'];
            $temp = array();
            foreach ($governorates as $gov) {
                array_push($temp, $this->getGovernorateby('id', $gov));
            }
            $orgInfo['governorate'] = $temp;

//            $entity->setGovernorates($temp);
        }

        /**
         * Handling transfered Files
         */
        if (!empty($orgInfo['CRAttachment']['name'])) {
            $orgInfo['CRAttachment'] = $this->saveAttachment('CRAttachment', 'cr');
        }
        if (!empty($orgInfo['wireTransferAttachment']['name']) && !$editFlag) {
            $orgInfo['wireTransferAttachment'] = $this->saveAttachment('wireTransferAttachment', 'wr');
        }
        if (!empty($orgInfo['atpLicenseAttachment']['name']) && !$editFlag) {
            $orgInfo['atpLicenseAttachment'] = $this->saveAttachment('atpLicenseAttachment', 'atp');
        }
        if (!empty($orgInfo['atcLicenseAttachment']['name']) && !$editFlag) {
            $orgInfo['atcLicenseAttachment'] = $this->saveAttachment('atcLicenseAttachment', 'atc');
        }
        /**
         * Save Organization
         */
//        var_dump($entity->getGovernorates());exit;
        $this->query->setEntity('Organizations\Entity\Organization')->save($entity, $orgInfo);

        // saving organization meta
        $orgMetaModel = $action->getServiceLocator()->get('Organizations\Model\OrganizationMeta');
        $orgMetaModel->saveOrganizationMeta($entity, $orgInfo);

        // does not work in case of savestate or edit
        if (!$saveState) {

            if (isset($orgInfo['trainingManager_id'])) {
                // if creater choosed someone with him as TM
                if (!empty($orgInfo['trainingManager_id']) && $orgInfo['trainingManager_id'] != $creatorId) {

                    $this->assignUserToOrg($entity, $orgInfo['trainingManager_id'], $rolesIds[Role::TRAINING_MANAGER_ROLE]);
                    $this->assignUserToOrg($entity, $creatorId, $rolesIds[Role::TRAINING_MANAGER_ROLE]);
                }
                // creator selected himself as TM
                else if ($orgInfo['trainingManager_id'] != 0) {
                    $this->assignUserToOrg($entity, $creatorId, $rolesIds[Role::TRAINING_MANAGER_ROLE]);
                }
                //creator left TM empty
                else if (empty($orgInfo['trainingManager_id']) && !$editFlag) {
                    $this->assignUserToOrg($entity, $creatorId, $rolesIds[Role::TRAINING_MANAGER_ROLE]);
                }
            }


            if (isset($orgInfo['testCenterAdmin_id'])) {
                if (!empty($orgInfo['testCenterAdmin_id']) && $orgInfo['testCenterAdmin_id'] != $creatorId) {
                    $this->assignUserToOrg($entity, $orgInfo['testCenterAdmin_id'], $rolesIds[Role::TEST_CENTER_ADMIN_ROLE]);
                    $this->assignUserToOrg($entity, $creatorId, $rolesIds[Role::TEST_CENTER_ADMIN_ROLE]);
                }
                else if ($orgInfo['testCenterAdmin_id'] != 0) {
                    $this->assignUserToOrg($entity, $creatorId, $rolesIds[Role::TEST_CENTER_ADMIN_ROLE]);
                }
                else if (empty($orgInfo['testCenterAdmin_id']) && !$editFlag) {
                    $this->assignUserToOrg($entity, $creatorId, $rolesIds[Role::TEST_CENTER_ADMIN_ROLE]);
                }
            }

            if ($sendNotificationFlag === true) {
                $this->sendMail($userEmail, $editFlag);
            }

            // redirecting
            $organizationTypes = $this->getOrganizationTypes(null, $entity);
            // redirection is based on first type of organization
            switch ($organizationTypes[0]) {
                case 1:
                    $url = $action->getEvent()->getRouter()->assemble(array('action' => 'atps'), array('name' => 'list_atc_orgs'));
                    break;

                case 2:
                    $url = $action->getEvent()->getRouter()->assemble(array('action' => 'atps'), array('name' => 'list_atc_orgs'));
                    break;

                case 3:
                    $url = $action->getEvent()->getRouter()->assemble(array('action' => 'distributors'), array('name' => 'list_distributor_orgs'));
                    break;

                case 4:
                    $url = $action->getEvent()->getRouter()->assemble(array('action' => 'resellers'), array('name' => 'list_reseller_orgs'));
                    break;
            }

            $action->redirect()->toUrl($url);
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
        $org->setStatus(Status::STATUS_INACTIVE);
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

    public function checkExistance($commercialName)
    {
        $organization = $this->query->findOneBy(/* $entityName = */ 'Organizations\Entity\Organization', array(
            'commercialName' => $commercialName
        ));
        if ($organization == null) {
            return false;
        }
        return true;
    }

    public function checkSavedBefore($commercialName)
    {
        $organization = $this->query->findOneBy(/* $entityName = */ 'Organizations\Entity\Organization', array(
            'commercialName' => $commercialName
        ));
        // if there's no organization with this commerical name
        if ($organization == null) {
            return false;
        }
        // existed but type saved state
        if ($organization->getStatus() == Status::STATUS_STATE_SAVED) {
            $this->query->remove($organization);
            return false;
        }
        // if existed with status not saved state
        return true;
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
    public function prepareForDisplay($organizationsArray)
    {
        $OSArray = OrganizationEntity::getOSs();
        $langsArray = OrganizationEntity::getStaticLangs();
        $officeVersionsArray = OrganizationEntity::getOfficeVersions();
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
            if (array_key_exists($organization->officeLang, $langsArray)) {
                $organization->officeLangText = $langsArray[$organization->officeLang];
            }
            if (array_key_exists($organization->operatingSystemLang, $langsArray)) {
                $organization->operatingSystemLangText = $langsArray[$organization->operatingSystemLang];
            }
            if (array_key_exists($organization->officeVersion, $officeVersionsArray)) {
                $organization->officeVersionText = $officeVersionsArray[$organization->officeVersion];
            }
            if (array_key_exists($organization->operatingSystem, $OSArray)) {
                $organization->operatingSystemText = $OSArray[$organization->operatingSystem];
            }
        }
        return $organizationsArray;
    }

    public function hasSavedState($orgType, $creatorId)
    {
        $savedState = $this->query->findOneBy('Organizations\Entity\Organization', array(
            'creatorId' => $creatorId,
            'status' => Status::STATUS_STATE_SAVED,
            'type' => $orgType
        ));

        if ($savedState != null) {
            return $savedState->id;
        }

        return null;
    }

    /**
     * Get required roles
     * 
     * @access public
     * @param int $organizationType
     * 
     * @return array required roles
     */
    public function getRequiredRoles($organizationTypes)
    {
        $requiredRoles = array();
        foreach ($organizationTypes as $organizationType) {
            switch ((int) $organizationType) {
                case OrganizationEntity::TYPE_ATP:
                    $requiredRoles[] = Role::TRAINING_MANAGER_ROLE;
                    break;
                case OrganizationEntity::TYPE_ATC:
                    $requiredRoles[] = Role::TEST_CENTER_ADMIN_ROLE;
                    break;
            }
        }
        return $requiredRoles;
    }

    /**
     * function to return array of parameters
     * @param type $action
     * @return array
     */
    public function getOrganizationTypes($action = null, $organizationObj = null)
    {
        $params = array();
        if ($action != null) {
            for ($i = 1; $i <= $this->organizationTypesNumber; $i++) {
                if ($action->params('v' . $i) != null) {
                    array_push($params, $action->params('v' . $i));
                }
            }
        }
        else if ($organizationObj != null) {
            $typesArray = $this->query->findBy('Organizations\Entity\OrganizationMeta', array(
                'organization' => $organizationObj->getId()
            ));
            foreach ($typesArray as $type) {
                array_push($params, $type->getType()->getId());
            }
        }
        return $params;
    }

    /**
     * Prepare organization diff
     * 
     * @access public
     * 
     * @param \ArrayIterator $organizationComparisonData
     * @return \ArrayIterator prepared organization comparison data
     */
    public function prepareOrganizationDiff($organizationComparisonData)
    {
        $organizationComparisonArray = $organizationComparisonData->getArrayCopy();
        $organizationComparisonPreparedArray = $this->prepareForDisplay(reset($organizationComparisonArray));

        $locationChanged = false;
        if ($organizationComparisonPreparedArray["before"]->longtitude != $organizationComparisonPreparedArray["after"]->longtitude || $organizationComparisonPreparedArray["before"]->latitude != $organizationComparisonPreparedArray["after"]->latitude) {
            $locationChanged = true;
        }
        $organizationComparisonPreparedArray["after"]->locationChanged = $locationChanged;

        $attachmentsArray = array(
            'CRAttachment',
            'wireTransferAttachment',
            'atpLicenseAttachment',
            'atcLicenseAttachment'
        );
        foreach ($attachmentsArray as $attachment) {
            $attachmentChanged = false;
            $attachmentChangedText = $attachment . "Changed";
            if ($organizationComparisonPreparedArray["before"]->$attachment != $organizationComparisonPreparedArray["after"]->$attachment) {
                $attachmentChanged = true;
            }
            $organizationComparisonPreparedArray["after"]->$attachmentChangedText = $attachmentChanged;
        }

        $organizationComparisonPreparedData = new \ArrayIterator(array($organizationComparisonPreparedArray));
        return $organizationComparisonPreparedData;
    }

    /**
     * Get organization file
     * 
     * @access public
     * 
     * @param Organizations\Entity\Organization $organization
     * @param string $type
     * @param bool $notApproved
     * @return string file path
     */
    public function getFile($organization, $type, $notApproved)
    {
        $file = null;

        if ($notApproved !== false) {
            $organizationArray = array($organization);
            $organizationLogs = $this->version->getLogEntriesPerEntities(/* $entities = */ $organizationArray, /* $objectIds = */ array(), /* $objectClass = */ null, /* $status = */ Status::STATUS_NOT_APPROVED);
            $organizationComparisonData = $this->version->prepareDiffs($organizationArray, $organizationLogs);
            $organizationComparisonArray = $organizationComparisonData->getArrayCopy();
            $organizationComparison = reset($organizationComparisonArray);
            $organization = $organizationComparison["after"];
        }

        if (property_exists($organization, $type)) {
            $file = $organization->$type;
        }

        return $file;
    }

    /**
     * Send mail
     * 
     * @access private
     * @param string $userEmail
     * @param bool $editFlag
     * @throws \Exception From email is not set
     * @throws \Exception Admin email is not set
     * @throws \Exception Operations email is not set
     */
    private function sendMail($userEmail, $editFlag)
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
        if ($editFlag === false) {
            $templateName = MailTempates::NEW_ORGANIZATION_NOTIFICATION_TEMPLATE;
            $subject = MailSubjects::NEW_ORGANIZATION_NOTIFICATION_SUBJECT;
        }
        else {
            $templateName = MailTempates::UPDATED_ORGANIZATION_NOTIFICATION_TEMPLATE;
            $subject = MailSubjects::UPDATED_ORGANIZATION_NOTIFICATION_SUBJECT;
        }
        $notificationMailArray = array(
            'to' => $adminEmail,
            'from' => $from,
            'templateName' => $templateName,
            'templateParameters' => $templateParameters,
            'subject' => $subject,
        );
        $this->notification->notify($notificationMailArray);

        if ($editFlag === false) {
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

    /**
     * function to test url minpulation with organization types
     * 1- no one can put number > organization number or negative numbers
     * 2- no one can preform something like that 1/2/2
     * @param type $action
     * @return boolean
     */
    private function validateParamters($action)
    {
        $params = array();
        // if no parameters 
        if ($action->params("v1") == null) {
            return false;
        }

        for ($i = 1; $i <= $this->organizationTypesNumber; $i++) {
            // no one enters /1/2/2
            if (in_array($action->params('v' . $i), $params) && $action->params('v' . $i) != null) {
                return false;
            }
            else {
                array_push($params, $action->params('v' . $i));
            }
            // no one enters values larger than number of organizations number or less than 0
            if ($action->params('v' . $i) > $this->organizationTypesNumber ||
                    $action->params('v' . $i) < 0) {
                return false;
            }
        }
        return true;
    }

    // false validation back to types page 

    public function validateUrlParamters($action)
    {
        $validUrl = $this->validateParamters($action);

        if (!$validUrl) {
            $url = $action->getEvent()->getRouter()->assemble(array('action' => 'type'), array('name' => 'org_type'));
            return $action->redirect()->toUrl($url);
        }
    }

    /**
     * function to customize org form as the required organization types
     * if required role is Training manager means it's an Atp
     * if required role is testcenter admin means it's an Atc
     * if required roles are TM & TCA means it's an Atc&Atp type and so on
     * NOTE : only ATc or ATP types need field minpulations
     * @param type $rolesArray
     * @param array $options
     * @param array $action to use service locator to get skipped params
     * returns Organization\Form\OrgForm $form
     */
    public function customizeOrgForm($rolesArray, $options, $action)
    {
        $form = new OrgForm(/* name */null, $options);

//        if there's no atp or atc organizations needed ex /3 or /4 or /3/4
        if (empty($rolesArray)) {
            $form = $this->unsetAtpFields($form, $action);
            $form = $this->unsetAtcFields($form, $action);
        }
        else if (!(in_array(\Users\Entity\Role::TRAINING_MANAGER_ROLE, $rolesArray) &&
                in_array(\Users\Entity\Role::TEST_CENTER_ADMIN_ROLE, $rolesArray))) {

            foreach ($rolesArray as $role) {
                switch ($role) {
                    case \Users\Entity\Role::TRAINING_MANAGER_ROLE :
                        $form = $this->unsetAtcFields($form, $action);
                        break;
                    case \Users\Entity\Role::TEST_CENTER_ADMIN_ROLE :
                        $form = $this->unsetAtpFields($form, $action);
                        break;
                }
            }
        }
        return $form;
    }

    public function customizeOrgEditForm($options, $action, $orgId)
    {
        $form = new OrgForm(/* name */null, $options);
        $organizationTypes = $this->query->findBy('Organizations\Entity\OrganizationMeta', array(
            'organization' => $orgId
        ));

        $typeIds = array();
        foreach ($organizationTypes as $type) {
            array_push($typeIds, $type->getType()->getId());
        }

        $form->remove('wireTransferAttachment');
        $form->getInputFilter()->remove('wireTransferAttachment');

        $form->get('CRAttachment')->setAttribute('required', false);

        foreach ($typeIds as $type) {

            if (in_array(OrganizationEntity::TYPE_ATC, $typeIds) && in_array(OrganizationEntity::TYPE_ATP, $typeIds)) {

                $form = $this->UnsetAtcEditFields($form, $action);
                $form = $this->UnsetAtpEditFields($form, $action);
                // user can manager user from organizationuser mang
                $form->remove('trainingManager_id');
                $form->getInputFilter()->remove('trainingManager_id');
                // user can manager user from organizationuser mang
                $form->remove('testCenterAdmin_id');
                $form->getInputFilter()->remove('testCenterAdmin_id');
            }
            else if ($type == OrganizationEntity::TYPE_ATC) {
                $form = $this->unsetAtpFields($form, $action, true);
                $form = $this->UnsetAtcEditFields($form, $action);
            }
            else if ($type == OrganizationEntity::TYPE_ATP) {
                $form = $this->unsetAtcFields($form, $action, true);
                $form = $this->UnsetAtpEditFields($form, $action);
            }
            else {

                $form = $this->unsetAtpFields($form, $action, true);
                $form = $this->unsetAtcFields($form, $action, true);
                $form = $this->UnsetAtpEditFields($form, $action);
                $form = $this->UnsetAtcEditFields($form, $action);
            }
        }

        return $form;
    }

    /**
     * function removes atc fields 
     * @param Organization\Form\OrgForm $form
     * 
     * @return Organization\Form\OrgForm $form 
     */
    private function unsetAtpFields($form, $action, $editFlag = false)
    {
//        $form->getInputFilter()->get('testCenterAdmin_id')->setRequired(false);

        $atcSkippedParams = $action->getServiceLocator()->get('Config')['atcSkippedParams'];
        foreach ($atcSkippedParams as $paramName) {
            $form->remove($paramName);
            $form->getInputFilter()->remove($paramName);
        }

        if ($editFlag) {
            // user can manager user from organizationuser mang
            $form->remove('testCenterAdmin_id');
            $form->getInputFilter()->remove('testCenterAdmin_id');
        }

        return $form;
    }

    /**
     * function removes atp fields 
     * @param Organization\Form\OrgForm $form
     * 
     * @return Organization\Form\OrgForm $form 
     */
    private function unsetAtcFields($form, $action, $editFlag = false)
    {
//        $form->getInputFilter()->get('trainingManager_id')->setRequired(false);

        $atpSkippedParams = $action->getServiceLocator()->get('Config')['atpSkippedParams'];
        foreach ($atpSkippedParams as $paramName) {
            $form->remove($paramName);
            $form->getInputFilter()->remove($paramName);
        }

        // user can manager user from organizationuser mang
        if ($editFlag) {
            // user can edit it form OrgUser Crud
            $form->remove('trainingManager_id');
            $form->getInputFilter()->remove('trainingManager_id');
        }
        return $form;
    }

    private function UnsetAtcEditFields($form, $action)
    {
        $atcEditSkippedParams = $action->getServiceLocator()->get('Config')['atcEditSkippedParams'];
        // user can edit them from renewal fields
        foreach ($atcEditSkippedParams as $paramName) {
            $form->remove($paramName);
            $form->getInputFilter()->remove($paramName);
        }
        return $form;
    }

    private function UnsetAtpEditFields($form, $action)
    {
        $atpEditSkippedParams = $action->getServiceLocator()->get('Config')['atpEditSkippedParams'];
        // user can edit them from renewal fields
        foreach ($atpEditSkippedParams as $paramName) {
            $form->remove($paramName);
            $form->getInputFilter()->remove($paramName);
        }
        return $form;
    }

}
