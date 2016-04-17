<?php

namespace Organizations\Model;

use Organizations\Entity\Organization;
use Zend\Authentication\AuthenticationService;
use Users\Entity\Role;

/**
 * OrganizationUser Model
 * 
 * Handles OrganizationUser Entity related business
 * 
 * 
 * @property Utilities\Service\Query\Query $query
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
     * Set needed properties
     * 
     * @access public
     * @param Utilities\Service\Query\Query $query
     */
    public function __construct($query)
    {
        $this->query = $query;
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
        
        $this->sortProctors(/* $organizationId = */ $organizationUser->getOrganization()->getId());
        
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
            array_push($types, $org->getType()->getId());
        }
        if (in_array(Organization::TYPE_ATC, $types) || in_array(Organization::TYPE_ATP, $types)) {
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
            $organizationLat = $proctor->getOrganization()->getLatitude();
            $organizationLong = $proctor->getOrganization()->getLongitude();
            $proctorLat = $proctor->getUser()->getLatitude();
            $proctorLong = $proctor->getUser()->getLongitude();

            $theta = $organizationLong - $proctorLong;
            $distanceRepresentation = rad2deg(acos(sin(deg2rad($organizationLat)) * sin(deg2rad($proctorLat)) + cos(deg2rad($organizationLat)) * cos(deg2rad($proctorLat)) * cos(deg2rad($theta))));
            $proctor->setDistanceSort((int)$distanceRepresentation);
            $this->query->setEntity('Organizations\Entity\OrganizationUser')->save($proctor, /*$data =*/ array(), /*$flushAll =*/ false, /*$noFlush =*/ true);
        }
    }

}
