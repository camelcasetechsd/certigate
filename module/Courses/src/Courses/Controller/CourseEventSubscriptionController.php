<?php

namespace Courses\Controller;

use Utilities\Controller\ActionController;
use Zend\View\Model\ViewModel;
use Zend\Authentication\AuthenticationService;
use Courses\Form\CourseEventSubscriptionForm;

/**
 * Course event subscription Controller
 * 
 * course event subscriptions entries listing
 * 
 * 
 * 
 * @package courses
 * @subpackage controller
 */
class CourseEventSubscriptionController extends ActionController
{

    /**
     * Subscribe/ Unsubscribe in course event periodic notification
     * 
     * @access public
     * 
     * @return ViewModel
     */
    public function alertSubscribeAction()
    {
        $query = $this->getServiceLocator()->get('wrapperQuery');
        $courseEventSubscriptionModel = $this->getServiceLocator()->get('Courses\Model\CourseEventSubscription');
        $courseEventId = $this->params('id');
        $auth = new AuthenticationService();
        $currentUserId = $auth->getIdentity()["id"];
        
        $courseEventSubscriptionForm = $courseEventSubscriptionModel->getCourseEventSubscriptionForm($courseEventId, $currentUserId);
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost()->toArray();
            $courseEventSubscription = $courseEventSubscriptionForm->getObject();
            $courseEventSubscriptionForm->setInputFilter($courseEventSubscription->getInputFilter($query));
            $courseEventSubscriptionForm->setData($data);
            if ($courseEventSubscriptionForm->isValid()) {
                $courseEventSubscriptionModel->process($courseEventSubscription, $data);
            }
        }

        $url = $this->getEvent()->getRouter()->assemble(array(), array('name' => 'coursesCalendar'));
        return $this->redirect()->toUrl($url);
    }
    
    /**
     * Notify course event subscribers
     *
     * 
     * @access public
     */
    public function notifySubscribersAction()
    {
        $courseEventSubscriptionModel = $this->getServiceLocator()->get('Courses\Model\CourseEventSubscription');
        $courseEventSubscriptionModel->notifySubscribers();
    }
}
