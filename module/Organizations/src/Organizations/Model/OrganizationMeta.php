<?php

namespace Organizations\Model;

use Organizations\Entity\Organization as OrganizationEntity;
use Utilities\Service\DateNames;
use Utilities\Service\Status;
use Notifications\Service\MailTemplates;
use Notifications\Service\MailSubjects;
use System\Service\Settings;
use Utilities\Service\Paginator\PaginatorAdapter;
use Zend\Paginator\Paginator;
use Zend\Authentication\AuthenticationService;
use Organizations\Entity\OrganizationType as OrgType;

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

    use \Utilities\Service\Paginator\PaginatorTrait;

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
        $this->paginator = new Paginator(new PaginatorAdapter($query, "Organizations\Entity\OrganizationMeta"));
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
            }
            else {
                $orgMeta = $this->query->findOneBy('Organizations\Entity\OrganizationMeta', array(
                    'organization' => $orgEntity->getId(),
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

        if ((int) $type == OrganizationEntity::TYPE_ATC || (int) $type == OrganizationEntity::TYPE_ATP) {
            //finding which Expiration date needed  
            $dates = $this->getDateName((int) $type);
            // if for case of edit organization
            if (isset($data[$dates['gregorian']]) || isset($data[$dates['hijri']])) {
                // validate flag with only gregorian date
                $dateValidation = $this->validateDate($data[$dates['gregorian']], null);
                $orgMeta->setExpirationFlag($dateValidation);
                $orgMeta->setExpirationDate($data[$dates['gregorian']]);
                $orgMeta->setExpirationDateHj($data[$dates['hijri']]);
            }
        }
        else {
            // if type 3 or 4 they can not expire (has no expiration date)
            $orgMeta->setExpirationFlag(Status::STATUS_NOT_YET_EXPIRED);
            $orgMeta->setExpirationDate(null);
            $orgMeta->setExpirationDateHj(null);
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
        $dates = array();
        switch ((int) $type) {
            case OrganizationEntity::TYPE_ATC :
                $dates['gregorian'] = DateNames::ATC_EXPIRATION_DATE;
                $dates['hijri'] = DateNames::HIJRI_ATC_EXPIRATION_DATE;
                break;
            case OrganizationEntity::TYPE_ATP :
                $dates['gregorian'] = DateNames::ATP_EXPIRATION_DATE;
                $dates['hijri'] = DateNames::HIJRI_ATP_EXPIRATION_DATE;
                break;
        }
        return $dates;
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
        // Need to update status only for ATP & ATC Metas Only
        $organizations = $this->query->entityManager->createQuery('SELECT u FROM Organizations\Entity\OrganizationMeta u '
                        . 'WHERE u.type = ' . OrgType::TYPE_ATC . ' or'
                        . ' u.type =' . OrgType::TYPE_ATP)
                ->getResult();

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
            'templateName' => MailTemplates::ORGANIZATION_RENEWAL_TEMPLATE,
            'templateParameters' => array(
                'name' => $organization->getOrganization()->getCommercialName(),
                'type' => $organization->getType() == OrganizationEntity::TYPE_ATC ? OrganizationEntity::TYPE_ATC : OrganizationEntity::TYPE_ATP,
                'expDate' => $organization->getExpirationDate()
            ),
            'subject' => MailSubjects::ORGANIZATION_RENEWAL,
        );
        $this->notification->notify($notificationMailArray);
    }

    /**
     * function to get organizations assigned to current user
     * 
     * @param ActionController $action
     * @return array Organizations\Entity\Organization
     */
    public function getMyOrganizations()
    {/** normal case without dist& reseller */

//        $auth = new AuthenticationService();
//        $storage = $auth->getIdentity();
//        $userOrganizations = $this->query->findBy('Organizations\Entity\OrganizationUser', array(
//            'user' => $storage['id']
//        ));
//        $myOrganizations = array();
//        foreach ($userOrganizations as $userOrganization) {
//            $organizations = $this->query->findBy('Organizations\Entity\OrganizationMeta', array(
//                'organization' => $userOrganization->getOrganization()->getId()
//            ));
//            /**
//             * those are organization meta objects not pure organizations
//             */
//            foreach ($organizations as $organization) {
//                $organization->type = $organization->getType()->getTitle();
//                $organization->expirationDate == null ? $organization->expirationDate = 'NO Expiration Date' : $organization->expirationDate = $organization->expirationDate->format('d/m/Y');
//                array_push($myOrganizations, $organization);
//            }
//        }
//        return $myOrganizations;



        $auth = new AuthenticationService();
        $storage = $auth->getIdentity();

        $oragnizationUsers = $this->query->findBy('Organizations\Entity\OrganizationUser', array(
            'user' => $storage['id']
        ));

        $userCreatedOrganizations = $this->query->findBy('Organizations\Entity\Organization', array(
            'creatorId' => $storage['id']
        ));

        /**
         * NOTE: by the following steps we overcome the problem of 
         * having no organization users for Distributor & Reseller
         * Ex : organization of type Reseller only can not show up in 
         * listing my organization because it has no organization users
         * although it has a record in organzation meta
         * SO 
         * merge & unique (myOrgUsers + mycreatedOrgs)
         * then get my organizationsmetas    
         */
        $organizationList = array();
        $myOrganizations = array();
        foreach ($userCreatedOrganizations as $oragnization) {
            array_push($organizationList, $oragnization->getId());
        }
        foreach ($oragnizationUsers as $oragnization) {
            array_push($organizationList, $oragnization->getOrganization()->getId());
        }

        $organizationList = array_unique($organizationList);

        foreach ($organizationList as $organizationId) {
            $organizationMetas = $this->query->findBy('Organizations\Entity\OrganizationMeta', array(
                'organization' => $organizationId
            ));
            /**
             * one Organization my have more than 1 type
             */
            foreach ($organizationMetas as $meta) {
                $meta->type = $meta->getType()->getTitle();
                $meta->expirationDate == null ? $meta->expirationDate = 'NO Expiration Date' : $meta->expirationDate = $meta->expirationDate->format('d/m/Y');
                array_push($myOrganizations, $meta);
            }
        }
        return $myOrganizations;
    }

    /**
     * call method that filters with my organizations criteria
     * 
     * @access public
     */
    public function filterOragnizations()
    {
        $auth = new AuthenticationService();
        $storage = $auth->getIdentity();
        // we use the paginator adapter to set criterias that we filter with
        // along with parameters and methods 
        /**
         * we call method getMyOrganizations in 
         * organizationMeta repo which filter organization  
         * metas and sending paramters to it
         */
        $adapter = $this->paginator->getAdapter();
        $adapter->setQuery($this->query->setEntity('Organizations\Entity\OrganizationMeta')->entityRepository);
        $adapter->setMethodName("getMyOrganizations");
        $adapter->setParameters(array(
            "currentUserId" => $storage['id']
        ));
    }

}
