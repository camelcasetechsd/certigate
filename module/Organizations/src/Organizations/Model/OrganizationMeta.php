<?php

namespace Organizations\Model;

use Organizations\Entity\Organization;
use Utilities\Service\DateNames;
use Utilities\Service\Status;
use Utilities\Service\Time;
use Notifications\Service\MailTempates;
use Notifications\Service\MailSubjects;
use System\Service\Settings;

/**
 * OrganizationMeta Model
 * 
 * Handles OrganizationUser Entity related business
 * 
 * 
 * @property Utilities\Service\Query\Query $query
 * 
 * @package organizations
 * @subpackage model
 */
class OrganizationMeta
{

    /**
     *
     * @var Utilities\Service\Query\Query 
     */
    protected $query;

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
     * @access public
     * @param Utilities\Service\Query\Query $query
     */
    public function __construct($query, $systemCacheHandler, $notification)
    {
        $this->query = $query;
        $this->systemCacheHandler = $systemCacheHandler;
        $this->notification = $notification;
    }

    /**
     * Save OrganizationMeta
     * Add new role if user does not have it
     * 
     * @access public
     * @param Organizations\Entity\Organization $orgEntity
     * @param array $data ,default is empty array
     * @param boolean $editFlag defualt false for creation
     * 
     */
    public function saveOrganizationMeta($orgEntity, $data = array(), $editFlag = false)
    {

        $types = array();
        /**
         * type is hidden field taked url parameters as types of organization
         * in creation only
         * in case of edit we use the value of organization Id to get types 
         */
        if (!empty($data['type'])) {
            $types = $this->prepareTypes($data['type']);
        }
        // in case of edit organization 
        else {
            $typesArray = $this->query->findBy('Organizations\Entity\OrganizationMeta', array(
                'organization' => $orgEntity->getId()
            ));
            foreach ($typesArray as $type) {
                array_push($types, $type->getType()->getId());
            }
        }

        foreach ($types as $type) {
            if (!$editFlag) {
                $orgMeta = new \Organizations\Entity\OrganizationMeta();
            }else{
                $orgMeta = $this->query->findOneBy('Organizations\Entity\OrganizationMeta',array(
                    'organization'=> $orgEntity->getId(),
                    'type' => $type
                ));
            }
            $orgMeta->setOrganization($orgEntity);
            $orgMeta = $this->prepareDateValidation($orgMeta, $data, $type);
            $this->query->setEntity('Organizations\Entity\OrganizationMeta')->save($orgMeta);
        }
    }

    private function prepareTypes($path)
    {
        return explode('/', $path);
    }

    /**
     * function to set validation flag and convert date string into object
     * then set them to the $orgMeta object then return it back
     * @param Organizations\Entity\OrganizationMeta $orgMeta
     * @param array $data
     * @param int $type  // organization type id
     */
    private function prepareDateValidation($orgMeta, $data, $type)
    {
        // setting organization type
        $typeObj = $this->query->findOneBy('Organizations\Entity\OrganizationType', array(
            'id' => (int) $type
        ));
        $orgMeta->setType($typeObj);

        if ((int) $type == Organization::TYPE_ATC || (int) $type == Organization::TYPE_ATP) {
            //finding which Expiration date needed  
            $dateName = $this->getDateName((int) $type);
            $dateValidation = $this->validateDate($data[$dateName], null);
            $orgMeta->setExpirationFlag($dateValidation);
            $orgMeta->setExpirationDate($data[$dateName]);
        }
        else {
            // if type 3 or 4 they can not expire (has no expiration date)
            $orgMeta->setExpirationFlag(Status::STATUS_NOT_YET_EXPIRED);
            $orgMeta->setExpirationDate(null);
        }
        return $orgMeta;
    }

    /**
     * returns date name
     * @param intger $type
     * @return string $dateName
     */
    private function getDateName($type)
    {
        switch ((int) $type) {
            case Organization::TYPE_ATC :
                $dateName = DateNames::ATC_EXPIRATION_DATE;
                break;
            case Organization::TYPE_ATP :
                $dateName = DateNames::ATP_EXPIRATION_DATE;
                break;
        }
        return $dateName;
    }

    /**
     * function to return if the date is expired or not
     * @param \DateTime $date
     * @param str $timeDifferenceInDays
     * @return Boolean
     */
    private function validateDate($date, $timeDifferenceInDays)
    {
        if ($timeDifferenceInDays != null) {
            $timeNow = new \DateTime('now');
        }
        else {
            $timeNow = new \DateTime($timeDifferenceInDays);
        }
        if ($timeNow >= $date) {
            return Status::STATUS_EXPIRED;
        }
        return Status::STATUS_NOT_YET_EXPIRED;
    }

    // this fuc handles updating expirationDateFlag
    // and sending notification Mail to Orgnization Users before week form the expiration 
    public function updateExpirationFlag()
    {

        $organizations = $this->query->findAll('Organizations\Entity\OrganizationMeta');
        /**
         * First Updating ExpirationFlag 
         */
        foreach ($organizations as $organization) {
            $status = $this->validateDate($organization->getExpirationDate(), null);

            if ($status == Status::STATUS_EXPIRED) {
                $organization->setExpirationFlag(Status::STATUS_EXPIRED);
                $this->query->setEntity('Organizations\Entity\OrganizationMeta')->save($organization);
            }
            else {
                /**
                 * checking for expiration date before week to send notification
                 */
                $weekBeforeStatus = $this->validateDate($organization->getDate(), '+7 days');
                if ($weekBeforeStatus == Status::STATUS_EXPIRED) {
                    $this->notify($organization);
                }
            }
        }
    }

    private function notify($organization)
    {
        $forceFlush = (APPLICATION_ENV == "production" ) ? false : true;
        $cachedSystemData = $this->systemCacheHandler->getCachedSystemData($forceFlush);
        $settings = $cachedSystemData[CacheHandler::SETTINGS_KEY];

        if (array_key_exists(Settings::OPERATIONS_EMAIL, $settings)) {
            $operationsEmail = $settings[Settings::OPERATIONS_EMAIL];
        }

        if (!isset($operationsEmail)) {
            throw new \Exception("Operations email is not set");
        }

        $notificationMailArray = array(
            'to' => /* $organization->getOrganization()->getEmail() */ 'ahmedredamohamed01@gamil.com',
            'from' => /* $operationsEmail */'anawany@yahoo.com',
            'templateName' => MailTempates::ORGANIZATION_RENEWAL_TEMPLATE,
            'templateParameters' => array(
                'name' => $organization->getOrganization()->getCommercialName(),
                'type' => $organization->getType() == Organization::TYPE_ATC ? Organization::TYPE_ATC : Organization::TYPE_ATP,
                'expDate' => $organization->getExpirationDate()
            ),
            'subject' => MailSubjects::ORGANIZATION_RENEWAL,
        );
        $this->notification->notify($notificationMailArray);
    }

}
