<?php

namespace CertigateAcl\Service;

use Zend\Authentication\AuthenticationService;
use Users\Entity\Role;
use Utilities\Service\Status;
use Organizations\Entity\OrganizationType;

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
     * Validate Non-Admin user ORganization-related actions Access Control
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
                $url = $this->getNotacceptedAgreementUrl($storage, $roleArray, $atLeastOneRoleFlag);
            }
            if (!empty($url)) {
                $accessValid = false;
                $response->setStatusCode(302);
            }
        }
        return array(
            "isValid" => $accessValid,
            "redirectUrl" => $url,
        );
    }

    /**
     * Validate Non-Admin user Exam-related actions Access Control
     * 
     * @access public
     * 
     * @param Zend\Http\PhpEnvironment\Response $response
     * @param array $userData
     * @param Courses\Entity\ExamBook $examBook
     * @return array bool is access valid or not and redirect url if redirect is needed
     */
    public function validateExamAccessControl($response, $userData, $examBook)
    {
        $accessValid = true;
        $url = null;
        if (!in_array(Role::ADMIN_ROLE, $userData['roles'])) {
            $types = array(OrganizationType::TYPE_ATC_TITLE);
            $userIds = array($userData["id"]);
            $ids = array($examBook->getAtc()->getId());
            $userOrganizations = $this->query->setEntity("Organizations\Entity\Organization")->entityRepository->getOrganizationsBy($userIds, $types, Status::STATUS_ACTIVE, $ids);
            if (empty($userOrganizations)) {
                $response->setStatusCode(302);
                $url = $this->router->assemble(array(), array('name' => 'noaccess'));
                $accessValid = false;
            }
            if ($accessValid === true) {
                $url = $this->getNotacceptedAgreementUrl(/*$storage =*/ $userData, /*$roleArray =*/ array(Role::TEST_CENTER_ADMIN_ROLE), /*$atLeastOneRoleFlag =*/ false);
            }
            if (!empty($url)) {
                $accessValid = false;
                $response->setStatusCode(302);
            }
        }
        return array(
            "isValid" => $accessValid,
            "redirectUrl" => $url,
        );
    }

    /**
     * Generate url for missing agreements error
     * 
     * @access private
     * @param array $storage
     * @param mixed $roleArray
     * @param bool $atLeastOneRoleFlag ,default is false
     * @return string not accepted agreements error url
     */
    private function getNotacceptedAgreementUrl($storage, $roleArray, $atLeastOneRoleFlag = false)
    {
        $url = null;
        if (!is_array($roleArray)) {
            $roleArray = array($roleArray);
        }
        $notacceptedAgreementRoles = array();
        $acceptedAgreementRoles = array();
        foreach ($roleArray as $role) {
            if (!(isset($storage["agreements"][$role]) && (int) $storage["agreements"][$role] === Status::STATUS_ACTIVE)) {
                $notacceptedAgreementRoles[] = $role;
            }
            elseif ($atLeastOneRoleFlag === true) {
                $acceptedAgreementRoles[] = $role;
            }
        }
        if ((count($notacceptedAgreementRoles) > 0 && $atLeastOneRoleFlag === false) || ($atLeastOneRoleFlag === true && count($acceptedAgreementRoles) == 0)) {
            $glue = ", ";
            if ($atLeastOneRoleFlag === true) {
                $glue = ", or ";
            }
            $notacceptedAgreementRolesString = implode($glue, $notacceptedAgreementRoles);
            $url = $this->router->assemble(array('id' => $storage['id'], 'role' => $notacceptedAgreementRolesString), array('name' => 'noAgreement'));
        }
        return $url;
    }

}
