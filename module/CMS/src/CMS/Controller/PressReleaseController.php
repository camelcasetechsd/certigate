<?php

namespace CMS\Controller;

use Utilities\Controller\ActionController;
use Zend\View\Model\ViewModel;
use CMS\Form\PressReleaseSubscriptionForm;
use CMS\Entity\PressReleaseSubscription;

/**
 * PressReleaseController Controller
 * 
 * PressRelease entries listing
 * 
 * 
 * 
 * @package cms
 * @subpackage controller
 */
class PressReleaseController extends ActionController
{

    /**
     * List press releases
     * 
     * 
     * @access public
     * 
     * @return ViewModel
     */
    public function indexAction()
    {
        $variables = array();
        $objectUtilities = $this->getServiceLocator()->get('objectUtilities');
        $pageModel = $this->getServiceLocator()->get('CMS\Model\Page');
        $pressReleaseSubscriptionModel = $this->getServiceLocator()->get('CMS\Model\PressReleaseSubscription');

        $pageNumber = $this->getRequest()->getQuery('page');
        $pageModel->filterPressReleases();
        $pageModel->setPage($pageNumber);

        $pageNumbers = $pageModel->getPagesRange($pageNumber);
        $nextPageNumber = $pageModel->getNextPageNumber($pageNumber);
        $previousPageNumber = $pageModel->getPreviousPageNumber($pageNumber);

        $variables['pressReleases'] = $objectUtilities->prepareForDisplay($pageModel->getCurrentItems());
        $variables['pageNumbers'] = $pageNumbers;
        $variables['hasPages'] = ( count($pageNumbers) > 0 ) ? true : false;
        $variables['nextPageNumber'] = $nextPageNumber;
        $variables['previousPageNumber'] = $previousPageNumber;


        $subscriptionsStatus = $pressReleaseSubscriptionModel->getSubscriptionsStatus();
        if (!empty($subscriptionsStatus)) {
            $pressReleaseSubscriptionForm = new PressReleaseSubscriptionForm(/* $name = */ null, /* $options = */ reset($subscriptionsStatus));
            $variables['pressReleaseSubscriptionForm'] = $this->getFormView($pressReleaseSubscriptionForm);
        }
        $failureMessage = $this->params('failureMessage', null);
        $status = $this->params('status', null);
        $unsubscribeFlag = $this->params('unsubscribeFlag', null);
        if (!is_null($failureMessage) || !is_null($status) || !is_null($unsubscribeFlag)) {
            $variables["messages"] = $pressReleaseSubscriptionModel->getSubscriptionResult((bool)$status, (bool)$unsubscribeFlag, $failureMessage);
        }
        return new ViewModel($variables);
    }

    /**
     * Subscribe in press releases subscription
     * 
     * @access public
     * 
     * @return ViewModel
     */
    public function subscribeAction()
    {
        $status = false;

        $query = $this->getServiceLocator()->get('wrapperQuery')->setEntity('CMS\Entity\PressReleaseSubscription');
        $pressReleaseSubscriptionModel = $this->getServiceLocator()->get('CMS\Model\PressReleaseSubscription');

        $subscriptionsStatus = $pressReleaseSubscriptionModel->getSubscriptionsStatus();
        $subscriptionStatus = reset($subscriptionsStatus);
        $userId = key($subscriptionsStatus);
        $pressReleaseSubscriptionForm = new PressReleaseSubscriptionForm(/* $name = */ null, /* $options = */ $subscriptionStatus);
        $pressReleaseSubscription = new PressReleaseSubscription();

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost()->toArray();
            $data["user"] = $userId;
            $pressReleaseSubscriptionForm->setInputFilter($pressReleaseSubscription->getInputFilter($query));
            $pressReleaseSubscriptionForm->setData($data);
            $failureMessage = null;
            if ($pressReleaseSubscriptionForm->isValid()) {

                $query->save($pressReleaseSubscription, /* $data = */ array("user" => $userId));
                $status = true;
            }
            else {
                $failureMessage = $pressReleaseSubscriptionForm->getMessagesAsString(/* $includeFieldNameFlag = */ false);
            }
        }

        $url = $this->getEvent()->getRouter()->assemble(array(
            'action' => 'index', 
            'failureMessage' => $failureMessage,
            'status' => (int)$status,
            'unsubscribeFlag' => 0,
                ), array('name' => 'cmsPressReleaseList'));
        return $this->redirect()->toUrl($url);
    }

    /**
     * Unsubscribe from press releases subscription
     * 
     * @access public
     * 
     * @return ViewModel
     */
    public function unsubscribeAction()
    {
        $status = false;
        $query = $this->getServiceLocator()->get('wrapperQuery')->setEntity('CMS\Entity\PressReleaseSubscription');
        $pressReleaseSubscriptionModel = $this->getServiceLocator()->get('CMS\Model\PressReleaseSubscription');

        $subscriptionsStatus = $pressReleaseSubscriptionModel->getSubscriptionsStatus();
        $token = $this->params('token');
        $userId = $this->params('userId');
        $subscriptionResult = $pressReleaseSubscriptionModel->getSubscription($token, $userId, $subscriptionsStatus);
        $message = $subscriptionResult["message"];

        if (!is_null($subscriptionResult["pressReleaseSubscription"])) {
            $query->remove($subscriptionResult["pressReleaseSubscription"]);
            $status = true;
        }

        $url = $this->getEvent()->getRouter()->assemble(array(
            'action' => 'index',
            'failureMessage' => $message,
            'status' => (int)$status,
            'unsubscribeFlag' => 1,
                ), array('name' => 'cmsPressReleaseList'));
        return $this->redirect()->toUrl($url);
    }

}
