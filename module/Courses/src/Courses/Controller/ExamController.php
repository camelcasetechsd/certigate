<?php

namespace Courses\Controller;

use Utilities\Controller\ActionController;
use Zend\View\Model\ViewModel;
use Zend\Authentication\AuthenticationService;
use Users\Entity\Role;
use Courses\Form\ExamBookProctorForm;

/**
 * ExamController
 * 
 * 
 * 
 * 
 * 
 * @package courses
 * @subpackage controller
 */
class ExamController extends ActionController
{

    /**
     * function meant to update the request by cron job
     * php public/index.php updateTvtcStatus
     */
    public function updateTvtcStatusAction()
    {
//        $request = $this->getRequest();
//        // Making sure that we are running in a console and the user has not tricked our
//        // application into running this action from a public web server.
//        if (!$request instanceof ConsoleRequest) {
//            throw new \RuntimeException('You can only use this action from a console!');
//        }

        $query = $this->getServiceLocator()->get('wrapperQuery')->setEntity('Courses\Entity\Course');
        $requests = $query->findAll('Courses\Entity\ExamBook');
        foreach ($requests as $req) {
            // date differnce between created day and current moment
            $dateDiff = date_diff(date_create(), $req->createdAt);
            // null and does after 3 days
            if ($dateDiff->days >= 3 && $req->tvtcStatus == null) {
                // here you will notify admin
                $req->setTvtcStatus(\Courses\Entity\ExamBook::TVTC_PENDING);
                $query->save($req);
            }
        }
    }

    public function bookAction()
    {
        $variables = array();
        $config = $this->getServiceLocator()->get('Config');
        $query = $this->getServiceLocator()->get('wrapperQuery')->setEntity('Courses\Entity\Course');
        $options['query'] = $query;
        $options['userId'] = $this->storage["id"];
        $examBook = new \Courses\Entity\ExamBook();
        $examModel = $this->getServiceLocator()->get('Courses\Model\Exam');
        
        $validationResult = $this->getServiceLocator()->get('aclValidator')->validateOrganizationAccessControl(/*$response =*/$this->getResponse(), /*$role =*/Role::TEST_CENTER_ADMIN_ROLE);
        if($validationResult["isValid"] === false && !empty($validationResult["redirectUrl"])){
            return $this->redirect()->toUrl($validationResult["redirectUrl"]);
        }
        
        $auth = new AuthenticationService();
        $storage = $auth->getIdentity();
        //checking if user is admin or test center admin
        if ($auth->hasIdentity()) {
            if (! (in_array(Role::ADMIN_ROLE, $storage['roles']) || in_array(Role::TEST_CENTER_ADMIN_ROLE, $storage['roles']))) {
                $this->getResponse()->setStatusCode(302);
                $url = $this->getEvent()->getRouter()->assemble(array(), array('name' => 'noaccess'));
                $this->redirect()->toUrl($url);
            }
        }

        $form = new \Courses\Form\BookExam(null, $options);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost()->toArray();
            $form->setInputFilter($examBook->getInputFilter());
            $form->setData($data);
            if ($form->isValid()) {
                // save exam rquest
                $examModel->saveBookingRequest($data,$config);
                // redirect
                $url = $this->getEvent()->getRouter()->assemble(/* $params = */ array('action' => 'calendar'), /* $routeName = */ array('name' => "coursesCalendar"));
                $this->redirect()->toUrl($url);
            }
        }
        $variables['bookExamForm'] = $this->getFormView($form);
        return new ViewModel($variables);
    }

    public function requestsAction()
    {
        $isAdminUser = $this->isAdminUser();
        $variables = array();
        $examModel = $this->getServiceLocator()->get('Courses\Model\Exam');

        $requests = $examModel->listRequests($isAdminUser, /*$userData =*/ $this->storage);
        $variables['requests'] = $requests;
        $variables['isAdminUser'] = $isAdminUser;
        return new ViewModel($variables);
    }

    /**
     * function meant to update the request in case the admin approved the request
     */
    public function acceptAction()
    {
        $requestId = $this->params('id');
        $examModel = $this->getServiceLocator()->get('Courses\Model\Exam');
        // no one can fake admin approval
        $auth = new AuthenticationService();
        $storage = $auth->getIdentity();
        //checking if user is admin or test center admin
        if ($auth->hasIdentity()) {
            if (! in_array(Role::ADMIN_ROLE, $storage['roles'])) {
               
                $this->getResponse()->setStatusCode(302);
                $url = $this->getEvent()->getRouter()->assemble(array(), array('name' => 'noaccess'));
                $this->redirect()->toUrl($url);
            }
        }

        $examModel->respondeToExamRequest(\Courses\Entity\ExamBook::ADMIN_APPROVED, $requestId);
        $url = $this->getEvent()->getRouter()->assemble(array('action' => 'requests'), array('name' => 'examRequests'));
        $this->redirect()->toUrl($url);
    }

    /**
     * function meant to update the request in case the admin declined the request
     */
    public function declineAction()
    {
        $requestId = $this->params('id');
        // no one can fake admin approval
        $auth = new AuthenticationService();
        $examModel = $this->getServiceLocator()->get('Courses\Model\Exam');
        $storage = $auth->getIdentity();
        //checking if user is admin or test center admin
        if ($auth->hasIdentity()) {
            if (! in_array(Role::ADMIN_ROLE, $storage['roles'])) {
               
                $this->getResponse()->setStatusCode(302);
                $url = $this->getEvent()->getRouter()->assemble(array(), array('name' => 'noaccess'));
                $this->redirect()->toUrl($url);
            }
        }

        $examModel->respondeToExamRequest(\Courses\Entity\ExamBook::ADMIN_DECLINED, $requestId);
        $url = $this->getEvent()->getRouter()->assemble(array('action' => 'requests'), array('name' => 'examRequests'));
        $this->redirect()->toUrl($url);
    }

    /**
     * function meant to update the request in case the tvtc Accepted the request
     */
    public function tvtcAcceptAction()
    {
        $requestId = $this->params('id');
        $examModel = $this->getServiceLocator()->get('Courses\Model\Exam');
        $examModel->respondeToExamRequest(\Courses\Entity\ExamBook::TVTC_APPROVED, $requestId, true);
        $url = $this->getEvent()->getRouter()->assemble(array('action' => 'index'), array('name' => 'home'));
        $this->redirect()->toUrl($url);
    }

    /**
     * function meant to update the request in case the tvtc declined the request
     */
    public function tvtcDeclineAction()
    {
        $requestId = $this->params('id');
        $examModel = $this->getServiceLocator()->get('Courses\Model\Exam');
        $examModel->respondeToExamRequest(\Courses\Entity\ExamBook::TVTC_DECLINED, $requestId, true);
        $url = $this->getEvent()->getRouter()->assemble(array('action' => 'index'), array('name' => 'home'));
        $this->redirect()->toUrl($url);
    }

    /**
     * Edit Exam Proctors
     * 
     * 
     * @access public
     * @uses ExamBookProctorForm
     * 
     * @return ViewModel
     */
    public function proctorsAction()
    {
        $variables = array();
        $id = $this->params('id');
        $query = $this->getServiceLocator()->get('wrapperQuery');
        $examBook = $query->find('Courses\Entity\ExamBook', $id);

        $validationResult = $this->getServiceLocator()->get('aclValidator')->validateExamAccessControl(/* $response = */$this->getResponse(), /* $userData = */$this->storage, $examBook);
        if ($validationResult["isValid"] === false && !empty($validationResult["redirectUrl"])) {
            return $this->redirect()->toUrl($validationResult["redirectUrl"]);
        }

        $options = array();
        $options['query'] = $query;
        $options['applicationLocale'] = $this->getServiceLocator()->get('applicationLocale');
        $form = new ExamBookProctorForm(/* $name = */ null, $options);
        $form->bind($examBook);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost()->toArray();
            $form->setData($data);

            if ($form->isValid()) {
                $query->setEntity("Courses\Entity\ExamBook")->save($examBook, /*$data =*/ array(),/*$flushAll =*/ true);
                $form->bind($examBook);
            }
        }

        $variables['examBookProctorForm'] = $this->getFormView($form);
        return new ViewModel($variables);
    }
}
