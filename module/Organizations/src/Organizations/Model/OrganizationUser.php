<?php

namespace Organizations\Model;

use Users\Entity\Role;
use Zend\Authentication\AuthenticationService;

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

    public function validateOrganizationUsers($organizations)
    {
        $auth = new AuthenticationService();
        $storage = $auth->getIdentity();

        foreach ($organizations as $organization) {
            $orgUsers = $organization->getOrganizationUsers();

            $users = array();
            foreach ($orgUsers as $orgUser) {
                array_push($users, $orgUser->getUser()->getId());
            }

            in_array($storage['id'], $users) ? $organization->orgUser = true : $organization->orgUser = false;

        }
        return $organizations;
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
        return false;
    }

}
