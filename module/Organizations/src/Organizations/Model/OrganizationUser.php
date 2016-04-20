<?php

namespace Organizations\Model;

use Organizations\Entity\OrganizationType;
use Zend\Authentication\AuthenticationService;
use Users\Entity\Role;

/**
 * OrganizationUser Model
 * 
 * Handles OrganizationUser Entity related business
 * 
 * 
 * @property Utilities\Service\Query\Query $query
 * @property Utilities\Service\Distance $distance
 * 
 * @package organizations
 * @subpackage model
 */
class OrganizationUser
{

    /**
     *
     * @var Utilities\Service\Query\Query 
     */
    protected $query;

    /**
     *
     * @var Utilities\Service\Distance 
     */
    protected $distance;

    /**
     * Set needed properties
     * 
     * @access public
     * @param Utilities\Service\Query\Query $query
     * @param Utilities\Service\Distance $distance
     */
    public function __construct($query, $distance)
    {
        $this->query = $query;
        $this->distance = $distance;
    }

    /**
     * Save OrganizationUser
     * Add new role if user does not have it
     * 
     * @access public
     * @param Organizations\Entity\OrganizationUser $organizationUser
     * @param array $data ,default is empty array
     */
    public function save($organizationUser, $data = array())
    {
        $this->query->setEntity('Organizations\Entity\OrganizationUser')->save($organizationUser, $data);
        
        $this->sortProctors(/* $organizationId = */ $organizationUser->getOrganization()->getId(), /* $userId = */ $organizationUser->getUser()->getId());
        
        $roleExists = false;
        $user = $organizationUser->getUser();
        foreach ($user->getRoles() as $role) {
            if ($role->getName() == $organizationUser->getRole()->getName()) {
                $roleExists = true;
                break;
            }
        }
        if ($roleExists === false) {
            $user->addRole($organizationUser->getRole());
            $this->query->setEntity('Users\Entity\User')->save($user, /* $data = */ array());
        }
    }

    /**
     * function limits user access on organization users pages if
     * organization type has no organization users
     * @param int $organizationId
     * @return boolean
     */
    public function validateOrganizationType($organizationId)
    {
        $organizationMeta = $this->query->findBy('Organizations\Entity\OrganizationMeta', array(
            'organization' => $organizationId
        ));

        $types = array();
        foreach ($organizationMeta as $org) {
            array_push($types, $org->getType()->getTitle());
        }
        if (in_array(OrganizationType::TYPE_ATC_TITLE, $types) || in_array(OrganizationType::TYPE_ATP_TITLE, $types)) {
            return true;
        }
        return false;
    }

    /**
     * function checks if the currnt user is a organization user (TCA || TM)
     * in given Oragnization
     * 
     * @param type $organizationObj
     * @return boolean
     */
    public function isOrganizationUser($action = null, $organizationObj)
    {
        $organizationUsers = $organizationObj->organizationUser;
        $usersIds = array();
        foreach ($organizationUsers as $user) {
            array_push($usersIds, $user->getUser()->getId());
        }

        $auth = new AuthenticationService();
        $storage = $auth->getIdentity();
        // if organization is ATP or ATC
        if (in_array($storage['id'], $usersIds)) {
            return true;
        }

        // if Organization DIST or Resselers so we need to check with creator id
        if ($action != null) {
            $orgCreatorId = $organizationObj->getCreatorId();
            if ($orgCreatorId == $storage['id']) {
                return true;
            }
        }
        return false;
    }

    public function isAdmin()
    {
        $auth = new AuthenticationService();
        $storage = $auth->getIdentity();

        if ($auth->hasIdentity()) {
            if (in_array(Role::ADMIN_ROLE, $storage['roles'])) {
                return true;
            }
            return false;
        }
    }
    
    /**
     * Sort proctors by distance
     * 
     * @access public
     * @param int $organizationId ,default is null
     * @param int $userId ,default is null
     */
    public function sortProctors($organizationId = null, $userId = null)
    {
        $role = $this->query->findOneBy(/* $entityName = */'Users\Entity\Role', /* $criteria = */ array(
                'name' => Role::PROCTOR_ROLE,
            ));
        $criteria = array("role" => $role);
        if (! is_null($organizationId)) {
            $criteria["organization"] = $organizationId;
        }
        if (! is_null($userId)) {
            $criteria["user"] = $userId;
        }
        
        $proctors = $this->query->findBy(/* $entityName = */'Organizations\Entity\OrganizationUser', $criteria);
        foreach ($proctors as $proctor) {
            $organizationLat = $proctor->getOrganization()->getLat();
            $organizationLong = $proctor->getOrganization()->getLong();
            $proctorLat = $proctor->getUser()->getLatitude();
            $proctorLong = $proctor->getUser()->getLongitude();

            // using Haversine formula to calculate the distance between two points (given the latitude/longitude of those points)
            $distance = $this->distance->getDistance(/*$latitudeFrom =*/ $organizationLat, /*$longitudeFrom =*/ $organizationLong, /*$latitudeTo =*/ $proctorLat, /*$longitudeTo =*/ $proctorLong);
            $proctor->setDistanceSort($distance);
            $this->query->setEntity('Organizations\Entity\OrganizationUser')->save($proctor, /*$data =*/ array(), /*$flushAll =*/ false, /*$noFlush =*/ true);
        }
        $this->query->entityManager->flush();
    }

}
