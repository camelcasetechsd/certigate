<?php

namespace Organizations\Model;

use Utilities\Service\Random;
use Zend\File\Transfer\Adapter\Http;
use DateTime;
use Utilities\Service\Status;
use Utilities\Service\Query\Query;

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
 * @package orgs
 * @subpackage model
 * 
 * 
 * 
 */
class Organization
{

    protected $CR_ATTACHMENT_PATH = 'public/upload/attachments/crAttachments/';
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

    public function saveOrganization($orgInfo, $orgObj = null)
    {

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
        if (!empty($orgInfo['trainingManager_id']) && $orgInfo['trainingManager_id'] != 0) {
            $orgInfo['trainingManager_id'] = $this->getUserby('id', $orgInfo['trainingManager_id'])[0];
        }
        else if (isset($orgInfo['trainingManager_id']) && $orgInfo['trainingManager_id'] == 0) {
            $orgInfo['trainingManager_id'] = null;
        }


        // test admin can be null if not selected 
        if (!empty($orgInfo['testCenterAdmin_id']) && $orgInfo['testCenterAdmin_id'] != 0) {
            $orgInfo['testCenterAdmin_id'] = $this->getUserby('id', $orgInfo['testCenterAdmin_id'])[0];
        }
        else if (isset($orgInfo['testCenterAdmin_id']) && $orgInfo['testCenterAdmin_id'] == 0) {
            $orgInfo['testCenterAdmin_id'] = null;
        }

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
        if (!empty($orgInfo['atpLicenseAttachment']['name'])) {
            $orgInfo['atpLicenseAttachment'] = $this->saveAttachment('atpLicenseAttachment', 'atp');
        }
        if (!empty($orgInfo['atcLicenseAttachment']['name'])) {
            $orgInfo['atcLicenseAttachment'] = $this->saveAttachment('atcLicenseAttachment', 'atc');
        }








//        
//        if (!empty($orgInfo['CRAttachment']['name']) && $orgInfo['CRAttachment']['name'] != '') {
//            $orgInfo['CRAttachment'] = $this->saveAttachment('CRAttachment', 'cr');
//        }
//        else {
//            $orgInfo['CRAttachment'] = null;
//        }
//        if (!empty($orgInfo['atpLicenseAttachment']) && $orgInfo['atpLicenseAttachment']['name'] != '') {
//            $orgInfo['atpLicenseAttachment'] = $this->saveAttachment('atpLicenseAttachment', 'atp');
//        }
//        else {
//            $orgInfo['atpLicenseAttachment'] = null;
//        }
//        if (!empty($orgInfo['atcLicenseAttachment']) && $orgInfo['atcLicenseAttachment']['name'] != '') {
//            $orgInfo['atcLicenseAttachment'] = $this->saveAttachment('atcLicenseAttachment', 'atc');
//        }
//        else {
//            $orgInfo['atcLicenseAttachment'] = null;
//        }
        /**
         * Save Organization
         */
        $this->query->setEntity('Organizations\Entity\Organization')->save($entity, $orgInfo);
    }

    private function saveAttachment($filename, $type)
    {
        switch ($type) {
            case 'cr':
                $uploadResult = $this->uploadAttachment($filename, $this->CR_ATTACHMENT_PATH);
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
            var_dump($e);
            exit;
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
        $staticOs = array(
            '0' => 'Microsoft Windows XP',
            '1' => 'Microsoft Windows Vista',
            '2' => 'Microsoft Windows 7',
            '3' => 'Microsoft Windows 8',
            '4' => 'Microsoft Windows 8.1',
            '5' => 'Microsoft Windows 10',
            '6' => 'Ubuntu Linux 13.04 LTS',
            '7' => 'Ubuntu Linux 14.04 LTS',
            '8' => 'Red Hat Enterprise Linux 5',
            '9' => 'Red Hat Enterprise Linux 6',
            '10' => 'Red Hat Enterprise Linux 7'
        );
        $staticLangs = array(
            '0' => 'Arabic',
            '1' => 'English',
            '2' => 'Deutsch',
            '3' => 'French',
            '4' => 'Japanese',
            '5' => 'Chinese',
        );

        $staticVersions = array(
                    '0' => 'Office 2000',
                    '1' => 'Office XP (2002)',
                    '2' => 'Office 2003',
                    '3' => 'Office 2007',
                    '4' => 'Office 2010',
                    '5' => 'Office 2013',
                    '6' => 'Office 2016',
                );
        $variables['userData']->operatingSystem = $staticOs[$variables['userData']->operatingSystem];
        $variables['userData']->operatingSystemLang = $staticLangs[$variables['userData']->operatingSystemLang];
        $variables['userData']->officeLang = $staticLangs[$variables['userData']->officeLang];
        $variables['userData']->officeVersion = $staticVersions[$variables['userData']->officeVersion];
        
        return $variables;
    }

}
