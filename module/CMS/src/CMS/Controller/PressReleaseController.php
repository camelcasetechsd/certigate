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
        $pressReleaseSubscriptionForm = new PressReleaseSubscriptionForm(/* $name = */ null, /* $options = */ $subscriptionStatus);
        $pressReleaseSubscription = new PressReleaseSubscription();

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost()->toArray();
            $pressReleaseSubscriptionForm->setInputFilter($pressReleaseSubscription->getInputFilter($query));
            $pressReleaseSubscriptionForm->setData($data);
            if ($pressReleaseSubscriptionForm->isValid()) {
                $userId = key($subscriptionsStatus);
                $query->save($pressReleaseSubscription, /* $data = */ array("user" => $userId));
                $status = true;
            }
        }

        return $this->getResponse()->setContent($pressReleaseSubscriptionModel->getSubscriptionResult($status, /* $unsubscribeFlag */ false, /*$message =*/ false, /*$jsonFlag =*/ true));
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
        $subscriptionResult = $pressReleaseSubscriptionModel->getSubscription($token, $subscriptionsStatus);
        $message = $subscriptionResult["message"];
        
        if (!is_null($subscriptionResult["pressReleaseSubscription"])) {
            $query->remove($subscriptionResult["pressReleaseSubscription"]);
            $status = true;
        }

        return $this->getResponse()->setContent($pressReleaseSubscriptionModel->getSubscriptionResult($status, /* $unsubscribeFlag */ true, $message, /*$jsonFlag =*/ (empty($token)) ? true : false));
    }

}
