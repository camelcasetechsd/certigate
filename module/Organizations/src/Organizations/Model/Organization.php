<?php

namespace Organizations\Model;

use Utilities\Service\Random;
use Zend\File\Transfer\Adapter\Http;
use DateTime;
use Utilities\Service\Status;
use Utilities\Service\Query\Query;
use Users\Entity\Role;

/**
 * Org Model
 * 
 * Handles Org Entity related business
 * 
 * 
 * 
 * @property Utilities\Service\Query\Query $query
 * @property Utilities\Service\Random $random
 * 
 * @package organizations
 * @subpackage model
 * 
 * 
 * 
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
     * Set needed properties
     * 
     * 
     * @access public
     * @uses Random
     * 
     * @param Utilities\Service\Query\Query $query
     */
    public function __construct($query)
    {
        $this->query = $query;
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

    public function saveOrganization($orgInfo, $orgObj = null, $creatorId = null, $saveState = false)
    {

        $roles = $this->query->findAll('Users\Entity\Role');
        $rolesIds = array();
        foreach ($roles as $role) {
            $rolesIds[$role->getName()] = $role->getId();
        }

        if (is_null($orgObj)) {

            $entity = new \Organizations\Entity\Organization();
        }
        else {
            $entity = $orgObj;
        }

//       
        /**
         * Handling convert string date to datetime object
         */
        if (!empty($orgInfo['CRExpiration'])) {
            $date = new DateTime($orgInfo['CRExpiration']);
            $orgInfo['CRExpiration'] = $date;
        }

        if (!empty($orgInfo['atcLicenseExpiration']) && $orgInfo['atcLicenseExpiration'] != "") {
            $date = new DateTime($orgInfo['atcLicenseExpiration']);
            $orgInfo['atcLicenseExpiration'] = $date;
        }
        else {
            $orgInfo['atcLicenseExpiration'] = null;
        }
        if (!empty($orgInfo['atpLicenseExpiration']) && $orgInfo['atpLicenseExpiration'] != "") {
            $date = new DateTime($orgInfo['atpLicenseExpiration']);
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
        if (!$saveState) {
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

}
