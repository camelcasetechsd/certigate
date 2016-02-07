<?php

namespace CertigateAcl\Service;

use Zend\Authentication\AuthenticationService;
use Users\Entity\Role;
use Utilities\Service\Status;

/**
 * AclValidator
 * 
 * Handle ACL validation business
 * 
 * @property Utilities\Service\Query\Query $query
 * @property Zend\Mvc\Router\RouteInterface $router
 * 
 * @package certigateAcl
 * @subpackage service
 */
class AclValidator
{

    /**
     *
     * @var Utilities\Service\Query\Query
     */
    protected $query;

    /**
     *
     * @var Zend\Mvc\Router\RouteInterface
     */
    protected $router;

    /**
     * Set needed properties
     * 
     * @access public
     * @param Utilities\Service\Query\Query $query
     * @param Zend\Mvc\Router\RouteInterface $router
     */
    public function __construct($query, $router)
    {
        $this->query = $query;
        $this->router = $router;
    }

    /**
     * Validate Access Control for actions
     * 
     * @access public
     * 
     * @param Zend\Http\PhpEnvironment\Response $response
     * @param mixed $roleArray string or array of roles
     * @param Organizations\Entity\Organization $organization ,default is null
     * @param bool $atLeastOneRoleFlag ,default is false
     * @return array bool is access valid or not and redirect url if redirect is needed
     */
    public function validateOrganizationAccessControl($response, $roleArray, $organization = null, $atLeastOneRoleFlag = false)
    {
        $accessValid = true;
        $url = null;
        $auth = new AuthenticationService();
        $storage = $auth->getIdentity();
        if ($auth->hasIdentity() && (!in_array(Role::ADMIN_ROLE, $storage['roles']) )) {
            if (!is_null($organization)) {
                $currentUserOrganizationUser = $this->query->findOneBy('Organizations\Entity\OrganizationUser', /* $criteria = */ array("user" => $storage['id'], "organization" => $organization->getId()));
                if (!is_object($currentUserOrganizationUser)) {
                    $url = $this->router->assemble(array(), array('name' => 'noaccess'));
                    $accessValid = false;
                }
            }
            if ($accessValid === true) {
                if (!is_array($roleArray)) {
                    $roleArray = array($roleArray);
                }
                $notacceptedAgreementRoles = array();
                $acceptedAgreementRoles = array();
                foreach ($roleArray as $role) {
                    if (!(isset($storage["agreements"][$role]) && (int) $storage["agreements"][$role] === Status::STATUS_ACTIVE)) {
                        $notacceptedAgreementRoles[] = $role;
                    }elseif ($atLeastOneRoleFlag === true) {
                        $acceptedAgreementRoles[] = $role;
                    }
                }
                if ((count($notacceptedAgreementRoles) > 0 && $atLeastOneRoleFlag === false) || ($atLeastOneRoleFlag === true && count($acceptedAgreementRoles) == 0)) {
                    $glue = ", ";
                    if($atLeastOneRoleFlag === true){
                        $glue = ", or ";
                    }
                    $notacceptedAgreementRolesString = implode($glue, $notacceptedAgreementRoles);
                    $url = $this->router->assemble(array('id' => $storage['id'], 'role' => $notacceptedAgreementRolesString), array('name' => 'noAgreement'));
                    $accessValid = false;
                }
            }
            if ($accessValid === false) {
                $response->setStatusCode(302);
            }
        }
        return array(
            "isValid" => $accessValid,
            "redirectUrl" => $url,
        );
    }

}
