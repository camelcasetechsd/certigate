<?php

namespace Courses\Controller;

use Utilities\Controller\ActionController;
use Zend\View\Model\ViewModel;
use Zend\Authentication\AuthenticationService;
use Users\Entity\Role;

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
        $request = $this->getRequest();
        // Making sure that we are running in a console and the user has not tricked our
        // application into running this action from a public web server.
        if (!$request instanceof ConsoleRequest) {
            throw new \RuntimeException('You can only use this action from a console!');
        }

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
        $examBook = new \Courses\Entity\ExamBook();
        $examModel = new \Courses\Model\Exam($query);
        $auth = new AuthenticationService();
        $storage = $auth->getIdentity();
        $isAdminUser = false;
        //checking if user is admin or test center admin
        if ($auth->hasIdentity()) {
            if (in_array(Role::ADMIN_ROLE, $storage['roles']) || in_array(Role::TEST_CENTER_ADMIN_ROLE, $storage['roles'])) {
                $isAdminUser = true;
            }
            else {
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
//              // redirect
                $url = $this->getEvent()->getRouter()->assemble(/* $params = */ array('action' => 'calendar'), /* $routeName = */ array('name' => "coursesCalendar"));
                $this->redirect()->toUrl($url);
            }
        }
        $variables['bookExamForm'] = $this->getFormView($form);
        return new ViewModel($variables);
    }

    public function requestsAction()
    {
        $variables = array();
        $query = $this->getServiceLocator()->get('wrapperQuery')->setEntity('Courses\Entity\Course');
        $examModel = new \Courses\Model\Exam($query);

        $auth = new AuthenticationService();
        $storage = $auth->getIdentity();
        $isAdminUser = false;
        //checking if user is admin or test center admin
        if ($auth->hasIdentity()) {
            // only admin can access this page
            if (in_array(Role::ADMIN_ROLE, $storage['roles'])) {
                $isAdminUser = true;
            }
            else {
                $this->getResponse()->setStatusCode(302);
                $url = $this->getEvent()->getRouter()->assemble(array(), array('name' => 'noaccess'));
                $this->redirect()->toUrl($url);
            }
        }

        $requests = $examModel->listRequests();
        $variables['requests'] = $requests;
        return new ViewModel($variables);
    }

    /**
     * function meant to update the request in case the admin approved the request
     */
    public function acceptAction()
    {
        $requestId = $this->params('id');
        $query = $this->getServiceLocator()->get('wrapperQuery')->setEntity('Courses\Entity\Course');
        $examModel = new \Courses\Model\Exam($query);
        // no one can fake admin approval
        $auth = new AuthenticationService();
        $storage = $auth->getIdentity();
        $isAdminUser = false;
        //checking if user is admin or test center admin
        if ($auth->hasIdentity()) {
            if (in_array(Role::ADMIN_ROLE, $storage['roles'])) {
                $isAdminUser = true;
            }
            else {
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
        $query = $this->getServiceLocator()->get('wrapperQuery')->setEntity('Courses\Entity\Course');
        $auth = new AuthenticationService();
        $examModel = new \Courses\Model\Exam($query);
        $storage = $auth->getIdentity();
        $isAdminUser = false;
        //checking if user is admin or test center admin
        if ($auth->hasIdentity()) {
            if (in_array(Role::ADMIN_ROLE, $storage['roles'])) {
                $isAdminUser = true;
            }
            else {
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
        $query = $this->getServiceLocator()->get('wrapperQuery')->setEntity('Courses\Entity\Course');
        $examModel = new \Courses\Model\Exam($query);
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
        $query = $this->getServiceLocator()->get('wrapperQuery')->setEntity('Courses\Entity\Course');
        $examModel = new \Courses\Model\Exam($query);
        $examModel->respondeToExamRequest(\Courses\Entity\ExamBook::TVTC_DECLINED, $requestId, true);
        $url = $this->getEvent()->getRouter()->assemble(array('action' => 'index'), array('name' => 'home'));
        $this->redirect()->toUrl($url);
    }

}
