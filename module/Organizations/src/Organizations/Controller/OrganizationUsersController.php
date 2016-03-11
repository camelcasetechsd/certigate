<?php

namespace Organizations\Controller;

use Utilities\Controller\ActionController;
use Zend\View\Model\ViewModel;
use Organizations\Form\OrganizationUserForm;
use Organizations\Entity\OrganizationUser;
use Zend\Authentication\AuthenticationService;
use Users\Entity\Role;
use Organizations\Entity\Organization;

/**
 * OrganizationUsers Controller
 * 
 * organizationUsers entries listing
 * 
 * 
 * 
 * @package organizations
 * @subpackage controller
 */
class OrganizationUsersController extends ActionController
{

    /**
     * List organizationUsers
     * 
     * 
     * @access public
     * 
     * @return ViewModel
     */
    public function indexAction()
    {
        $variables = array();
        $query = $this->getServiceLocator()->get('wrapperQuery')->setEntity('Organizations\Entity\OrganizationUser');
        $objectUtilities = $this->getServiceLocator()->get('objectUtilities');
        $organizationId = $this->params('organizationId');
        $organization = $query->find('Organizations\Entity\Organization', $organizationId);
        $auth = new AuthenticationService();
        $storage = $auth->getIdentity();

        $organizationModel = $this->getServiceLocator()->get('Organizations\Model\Organization');
        $rolesArray = $organizationModel->getRequiredRoles($organizationModel->getOrganizationTypes(null, $organization));
        $validationResult = $this->getServiceLocator()->get('aclValidator')->validateOrganizationAccessControl(/* $response = */$this->getResponse(), $rolesArray, $organization);
        if ($validationResult["isValid"] === false && !empty($validationResult["redirectUrl"])) {
            return $this->redirect()->toUrl($validationResult["redirectUrl"]);
        }

        $data = $query->findBy(/* $entityName = */'Organizations\Entity\OrganizationUser', /* $criteria = */ array('organization' => $organizationId));
        foreach ($data as $organizationUser) {
            $organizationUser->isCurrentUser = false;
            if ($storage['id'] == $organizationUser->getUser()->getId()) {
                $organizationUser->isCurrentUser = true;
            }
        }
        $variables['organizationUsers'] = $objectUtilities->prepareForDisplay($data);
        $variables['organizationId'] = $organizationId;
        return new ViewModel($variables);
    }

    /**
     * Create new OrganizationUser
     * 
     * 
     * @access public
     * @uses OrganizationUser
     * @uses OrganizationUserForm
     * 
     * @return ViewModel
     */
    public function newAction()
    {
        $variables = array();
        $organizationId = $this->params('organizationId');
        $query = $this->getServiceLocator()->get('wrapperQuery')->setEntity('Organizations\Entity\OrganizationUser');
        $organizationUserModel = $this->getServiceLocator()->get('Organizations\Model\OrganizationUser');
        $organizationModel = $this->getServiceLocator()->get('Organizations\Model\Organization');
        $organization = $query->find('Organizations\Entity\Organization', $organizationId);

        $rolesArray = $organizationModel->getRequiredRoles($organization->$organizationModel->getOrganizationTypes(null, $organization));
        $validationResult = $this->getServiceLocator()->get('aclValidator')->validateOrganizationAccessControl(/* $response = */$this->getResponse(), $rolesArray, $organization);
        if ($validationResult["isValid"] === false && !empty($validationResult["redirectUrl"])) {
            return $this->redirect()->toUrl($validationResult["redirectUrl"]);
        }

        $organizationUser = new OrganizationUser();

        $options = array();
        $options['query'] = $query;
        $options['organizationType'] = $organizationModel->getOrganizationTypes(null, $organization);
        $form = new OrganizationUserForm(/* $name = */ null, $options);
        // in order to set hidden organization field with id
        $form->get("organization")->setValue($organizationId);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost()->toArray();
            $form->setInputFilter($organizationUser->getInputFilter($query));
            $form->setData($data);
            if ($form->isValid()) {
                $organizationUserModel->save($organizationUser, $data);
                $url = $this->getEvent()->getRouter()->assemble(/* $params = */ array('action' => 'index', 'organizationId' => $organizationId), /* $routeName = */ array('name' => "organizationUsersList"));
                $this->redirect()->toUrl($url);
            }
        }

        $variables['organizationUserForm'] = $this->getFormView($form);
        return new ViewModel($variables);
    }

    /**
     * Edit organizationUser
     * 
     * 
     * @access public
     * @uses OrganizationUserForm
     * 
     * @return ViewModel
     */
    public function editAction()
    {
        $variables = array();
        $id = $this->params('id');
        $query = $this->getServiceLocator()->get('wrapperQuery');
        $organizationUserModel = $this->getServiceLocator()->get('Organizations\Model\OrganizationUser');
        $organizationModel = $this->getServiceLocator()->get('Organizations\Model\Organization');
        $organizationUser = $query->find('Organizations\Entity\OrganizationUser', $id);
        $organization = $organizationUser->getOrganization();
        $organizationId = $organization->getId();

        $rolesArray = $organizationModel->getRequiredRoles($organizationModel->getOrganizationTypes(null, $organization));
        $validationResult = $this->getServiceLocator()->get('aclValidator')->validateOrganizationAccessControl(/* $response = */$this->getResponse(), $rolesArray, $organization);
        if ($validationResult["isValid"] === false && !empty($validationResult["redirectUrl"])) {
            return $this->redirect()->toUrl($validationResult["redirectUrl"]);
        }

        $options = array();
        $options['query'] = $query;
        $options['organizationType'] = $organizationModel->getOrganizationTypes(null, $organization);
        $form = new OrganizationUserForm(/* $name = */ null, $options);
        // in order to set hidden organization field with id
        $organizationUser->setOrganization($organizationId);
        $form->bind($organizationUser);
        $organizationUser->setOrganization($organization);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost()->toArray();
            $form->setInputFilter($organizationUser->getInputFilter($query));
            $form->setData($data);

            if ($form->isValid()) {
                $organizationUserModel->save($organizationUser);
                $url = $this->getEvent()->getRouter()->assemble(/* $params = */ array('action' => 'index', 'organizationId' => $organizationId), /* $routeName = */ array('name' => "organizationUsersList"));
                $this->redirect()->toUrl($url);
            }
        }

        $variables['organizationUserForm'] = $this->getFormView($form);
        return new ViewModel($variables);
    }

    /**
     * Delete organizationUser
     *
     * 
     * @access public
     */
    public function deleteAction()
    {
        $id = $this->params('id');
        $query = $this->getServiceLocator()->get('wrapperQuery');
        $organizationUser = $query->find('Organizations\Entity\OrganizationUser', $id);
        $organizationModel = $this->getServiceLocator()->get('Organizations\Model\Organization');
        $organization = $organizationUser->getOrganization();
        $organizationId = $organization->getId();

        $rolesArray = $organizationModel->getRequiredRoles($organizationModel->getOrganizationTypes(null, $organization));
        $validationResult = $this->getServiceLocator()->get('aclValidator')->validateOrganizationAccessControl(/* $response = */$this->getResponse(), $rolesArray, $organization);
        if ($validationResult["isValid"] === false && !empty($validationResult["redirectUrl"])) {
            return $this->redirect()->toUrl($validationResult["redirectUrl"]);
        }

        $auth = new AuthenticationService();
        $storage = $auth->getIdentity();
        if ($storage['id'] == $organizationUser->getUser()->getId()) {
            $this->getResponse()->setStatusCode(302);
            $url = $this->getEvent()->getRouter()->assemble(array(), array('name' => 'noaccess'));
            $this->redirect()->toUrl($url);
        }
        else {
            $query->remove($organizationUser);
            $url = $this->getEvent()->getRouter()->assemble(array('action' => 'index', 'organizationId' => $organizationId), array('name' => 'organizationUsersList'));
            $this->redirect()->toUrl($url);
        }
    }

    /**
     * Validate Access Control for actions
     * 
     * @access private
     * @param Organizations\Entity\Organization $organization
     * @return bool is access valid or not
     */
    private function validateAccessControl($organization)
    {
        $accessValid = true;
        $auth = new AuthenticationService();
        $storage = $auth->getIdentity();
        $query = $this->getServiceLocator()->get('wrapperQuery');
        if ($auth->hasIdentity() && (!in_array(Role::ADMIN_ROLE, $storage['roles']) )) {
            $currentUserOrganizationUser = $query->findOneBy('Organizations\Entity\OrganizationUser', /* $criteria = */ array("user" => $storage['id'], "organization" => $organization->getId()));
            if ((!is_object($currentUserOrganizationUser)) || (!in_array(Role::TEST_CENTER_ADMIN_ROLE, $storage['roles']) && $organizationModel->getOrganizationTypes(null, $organization) == Organization::TYPE_ATC) || (!in_array(Role::TRAINING_MANAGER_ROLE, $storage['roles']) && $organizationModel->getOrganizationTypes(null, $organization) == Organization::TYPE_ATP)) {
                $accessValid = false;
            }
        }
        return $accessValid;
    }

}
