<?php

namespace Users\Controller;

use Utilities\Controller\ActionController;
use Zend\View\Model\ViewModel;
use Users\Form\UserForm;
use Users\Entity\User;
use Users\Service\Statement;
use Users\Entity\Role;
use Zend\Authentication\AuthenticationService;

/**
 * Index Controller
 * 
 * users entries listing for adminstration
 * 
 * 
 * 
 * @package users
 * @subpackage controller
 */
class IndexController extends ActionController
{

    /**
     * List instructors paginated
     * 
     * 
     * @access public
     * @return ViewModel
     */
    public function instructorsAction()
    {
        $variables = array();
        $objectUtilities = $this->getServiceLocator()->get('objectUtilities');
        $userModel = $this->getServiceLocator()->get('Users\Model\User');

        $pageNumber = $this->getRequest()->getQuery('page');
        $userModel->filterInstructors();
        $userModel->setPage($pageNumber);

        $pageNumbers = $userModel->getPagesRange($pageNumber);
        $variables['users'] = $objectUtilities->prepareForDisplay($userModel->getCurrentItems());
        $variables['pageNumbers'] = $pageNumbers;
        $variables['hasPages'] = ( count($pageNumbers) > 0 ) ? true : false;
        $variables['nextPageNumber'] = $userModel->getNextPageNumber($pageNumber);
        $variables['previousPageNumber'] = $userModel->getPreviousPageNumber($pageNumber);
        return new ViewModel($variables);
    }
    
    /**
     * More user details
     * 
     * 
     * @access public
     * @return ViewModel
     */
    public function moreAction()
    {
        $variables = array();
        $id = $this->params('id');
        $query = $this->getServiceLocator()->get('wrapperQuery');
        $objectUtilities = $this->getServiceLocator()->get('objectUtilities');
        $user = $query->find('Users\Entity\User', $id);
        $processedData = $objectUtilities->prepareForDisplay(array($user));
        $variables['user'] = $processedData;
        return new ViewModel($variables);
    }

    /**
     * List users
     * 
     * 
     * @access public
     * @return ViewModel
     */
    public function indexAction()
    {
        $variables = array();
        $query = $this->getServiceLocator()->get('wrapperQuery');
        $objectUtilities = $this->getServiceLocator()->get('objectUtilities');

        $data = $query->findAll('Users\Entity\User');
        // process data that will be displayed later
        $processedData = $objectUtilities->prepareForDisplay($data);

        $variables['userList'] = $processedData;
        return new ViewModel($variables);
    }

    /**
     * Edit user
     * 
     * 
     * @access public
     * @uses UserForm
     * 
     * @return ViewModel
     */
    public function editAction()
    {
        $variables = array();
        $id = $this->params('id');
        $query = $this->getServiceLocator()->get('wrapperQuery');
        $countriesService = $this->getServiceLocator()->get('losi18n-countries');
        $languagesService = $this->getServiceLocator()->get('losi18n-languages');
        $userModel = $this->getServiceLocator()->get('Users\Model\User');
        $userObj = $query->find('Users\Entity\User', $id);
        $oldLongitude = $userObj->getLongitude();
        $oldLatitude = $userObj->getLatitude();
        $photo = $userObj->photo;
        // allow access for admins for all users
        // restrict access for current user only for non-admin users
        $auth = new AuthenticationService();
        $storage = $auth->getIdentity();
        if (!in_array(Role::ADMIN_ROLE, $storage['roles']) && $id != $storage['id']) {
            $this->getResponse()->setStatusCode(302);
            $url = $this->getEvent()->getRouter()->assemble(array(), array('name' => 'noaccess'));
            $this->redirect()->toUrl($url);
        }

        $options = array();
        $options['query'] = $query;
        $options['countriesService'] = $countriesService;
        $options['languagesService'] = $languagesService;
        $options['applicationLocale'] = $this->getServiceLocator()->get('applicationLocale');
        
        $options['excludedRoles'] = array(Role::USER_ROLE);
        if (!$auth->hasIdentity() || ( $auth->hasIdentity() && !in_array(Role::ADMIN_ROLE, $storage['roles']))) {
            $options['excludedRoles'][] = Role::ADMIN_ROLE;
        }
        $isAdminUser = $this->isAdminUser();
        
        $form = new UserForm(/* $name = */ null, $options);
        $form->bind($userObj);

        $request = $this->getRequest();
        if ($request->isPost()) {
            
            // Make certain to merge the files info!
            $fileData = $request->getFiles()->toArray();

            $data = array_merge_recursive(
                    $request->getPost()->toArray(), $fileData
            );
            $query->setEntity('Users\Entity\User');
            $form->setInputFilter($userObj->getInputFilter($query, $data));
            $inputFilter = $form->getInputFilter();
            $form->setData($data);
            $isCustomValidationValid = true;


            // file not updated
            if (isset($fileData['photo']['name']) && empty($fileData['photo']['name'])) {
                // Change required flag to false for any previously uploaded files
                $input = $inputFilter->get('photo');
                $input->setRequired(false);
            }

            if (empty($data['email']) || trim($data['email']) == $userObj->getEmail()) {
                $email = $inputFilter->get('email');
                $email->setRequired(false);
                $confirmEmail = $inputFilter->get('confirmEmail');
                $confirmEmail->setRequired(false);
            }
            elseif ($data['email'] != $data['confirmEmail']) {
                $form->get('confirmEmail')->setMessages(array("email doesnt match"));
                $isCustomValidationValid = false;
            }

            if (empty($data['password'])) {
                $password = $inputFilter->get('password');
                $password->setRequired(false);
                $confirmPassword = $inputFilter->get('confirmPassword');
                $confirmPassword->setRequired(false);
            }
            elseif ($data['password'] != $data['confirmPassword']) {
                $form->get('confirmPassword')->setMessages(array("password doesnt match"));
                $isCustomValidationValid = false;
            }

            if ($form->isValid() && $isCustomValidationValid === true) {
                $userModel->saveUser($data, $userObj, $isAdminUser, /*$editFormFlag =*/ null, $oldLongitude, $oldLatitude);
                
                if($isAdminUser){
                $url = $this->getEvent()->getRouter()->assemble(array('action' => 'index'), array(
                    'name' => 'users'));
                $this->redirect()->toUrl($url);
                }else{
                    // bind form with latest updated object
                    $form->bind($userObj);
                    $variables['success'] = true;
                }
            }
        }

        $variables['userForm'] = $this->getFormView($form);
        $statement = new Statement();
        $variables['rolesStatements'] = $statement->rolesStatements;
        $variables['privacyStatement'] = $statement->privacyStatement;
        $variables['photo'] = $photo;
        return new ViewModel($variables);
    }

    /**
     * Create new user
     * 
     * 
     * @access public
     * @uses User
     * @uses UserForm
     * 
     * @return ViewModel
     */
    public function newAction()
    {

        $variables = array();
        $query = $this->getServiceLocator()->get('wrapperQuery')->setEntity('Users\Entity\User');
        $countriesService = $this->getServiceLocator()->get('losi18n-countries');
        $languagesService = $this->getServiceLocator()->get('losi18n-languages');
        $userModel = $this->getServiceLocator()->get('Users\Model\User');
        $userObj = new User();
        $options = array();
        $options['query'] = $query;
        $options['countriesService'] = $countriesService;
        $options['languagesService'] = $languagesService;
        $options['applicationLocale'] = $this->getServiceLocator()->get('applicationLocale');
        $options['excludedRoles'] = array(Role::USER_ROLE);
        $auth = new AuthenticationService();
        $storage = $auth->getIdentity();
        if (!$auth->hasIdentity() || ( $auth->hasIdentity() && !in_array(Role::ADMIN_ROLE, $storage['roles']))) {
            $options['excludedRoles'][] = Role::ADMIN_ROLE;
        }
        $isAdminUser = $this->isAdminUser();
        $form = new UserForm(/* $name = */ null, $options);

        $request = $this->getRequest();
        if ($request->isPost()) {

            // Make certain to merge the files info!
            $fileData = $request->getFiles()->toArray();

            $data = array_merge_recursive(
                    $request->getPost()->toArray(), $fileData
            );

            $query->setEntity('Users\Entity\User');
            $form->setInputFilter($userObj->getInputFilter($query, $data));
            $form->setData($data);
            $isCustomValidationValid = true;
            if ($data['email'] != $data['confirmEmail']) {
                $form->get('confirmEmail')->setMessages(array("email doesnt match"));
                $isCustomValidationValid = false;
            }
            if ($data['password'] != $data['confirmPassword']) {
                $form->get('confirmPassword')->setMessages(array("password doesnt match"));
                $isCustomValidationValid = false;
            }
            if ($form->isValid() && $isCustomValidationValid === true) {
                $userModel->saveUser($data , /*$userObj =*/ null ,$isAdminUser);

                if($isAdminUser){
                    $routeName = "users";
                }else{
                    $routeName = "home";
                }
                $url = $this->getEvent()->getRouter()->assemble(array('action' => 'index'), array(
                    'name' => $routeName));
                $this->redirect()->toUrl($url);
            }
        }

        $variables['userForm'] = $this->getFormView($form);
        $statement = new Statement();
        $variables['rolesStatements'] = $statement->rolesStatements;
        $variables['privacyStatement'] = $statement->privacyStatement;
        return new ViewModel($variables);
    }

    /**
     * Delete user
     * 
     * 
     * @access public
     */
    public function deleteAction()
    {
        $id = $this->params('id');
        $userModel = $this->getServiceLocator()->get('Users\Model\User');
        $userModel->deleteUser($id);
        $url = $this->getEvent()->getRouter()->assemble(array('action' => 'index'), array(
            'name' => 'users'));
        $this->redirect()->toUrl($url);
    }

    /**
     * Activate user
     * 
     * 
     * @access public
     */
    public function activateAction()
    {
        $id = $this->params('id');
        $userModel = $this->getServiceLocator()->get('Users\Model\User');
        $userModel->activateUser($id);
        $url = $this->getEvent()->getRouter()->assemble(array('action' => 'index'), array(
            'name' => 'users'));
        $this->redirect()->toUrl($url);
    }

}
