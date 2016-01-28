<?php

namespace Organizations\Controller;

use Utilities\Controller\ActionController;
use Zend\View\Model\ViewModel;
use Organizations\Form\OrganizationUserForm;
use Organizations\Entity\OrganizationUser;

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
        $organizationId = $this->params('organizationId');
        $variables = array();
        $query = $this->getServiceLocator()->get('wrapperQuery')->setEntity('Organizations\Entity\OrganizationUser');
        $objectUtilities = $this->getServiceLocator()->get('objectUtilities');

        $data = $query->findBy(/* $entityName = */'Organizations\Entity\OrganizationUser', /*$criteria =*/ array('organization' => $organizationId));
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
        $organization = $query->find('Organizations\Entity\Organization', $organizationId);
        $organizationUser = new OrganizationUser();
        
        $options = array();
        $options['query'] = $query;
        $options['organizationType'] = $organization->getType();
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
                $url = $this->getEvent()->getRouter()->assemble(/*$params =*/ array('action' => 'index', 'organizationId' => $organizationId), /*$routeName =*/ array('name' => "organizationUsersList"));
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
        $organizationUser = $query->find('Organizations\Entity\OrganizationUser', $id);
        $organization = $organizationUser->getOrganization();
        $organizationId = $organization->getId();
        
        $options = array();
        $options['query'] = $query;
        $options['organizationType'] = $organization->getType();
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
                $url = $this->getEvent()->getRouter()->assemble(/*$params =*/ array('action' => 'index', 'organizationId' => $organizationId), /*$routeName =*/ array('name' => "organizationUsersList"));
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
        $organizationId = $organizationUser->getOrganization()->getId();
        $query->remove($organizationUser);

        $url = $this->getEvent()->getRouter()->assemble(array('action' => 'index', 'organizationId' => $organizationId), array('name' => 'organizationUsersList'));
        $this->redirect()->toUrl($url);
    }

}
