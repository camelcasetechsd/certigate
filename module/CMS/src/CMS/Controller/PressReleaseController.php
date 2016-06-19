<?php

namespace CMS\Controller;

use Utilities\Controller\ActionController;
use Zend\View\Model\ViewModel;
use CMS\Form\PressReleaseSubscriptionForm;
use CMS\Entity\PressReleaseSubscription;
use Zend\Mime\Part as MimePart;
use Zend\Mime\Message as MimeMessage;
use Translation\Service\Locale\Locale;
use Utilities\Service\Status;
use TCPDF;

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

        $failureMessage = $status = $unsubscribeFlag = null;

        if (!empty($this->flashMessenger()->getErrorMessages())) {
            $failureMessage = $this->flashMessenger()->getErrorMessages()[0];
        }
        if (!empty($this->flashMessenger()->getInfoMessages())) {
            $status = $this->flashMessenger()->getInfoMessages()[0];
        }
        if (!empty($this->flashMessenger()->getInfoMessages())) {
            $unsubscribeFlag = $this->flashMessenger()->getInfoMessages()[1];
        }

        if (!is_null($failureMessage) || !is_null($status) || !is_null($unsubscribeFlag)) {
            $variables["messages"] = $pressReleaseSubscriptionModel->getSubscriptionResult((bool) $status, (bool) $unsubscribeFlag, $failureMessage);
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

            // delete all perivious messages
            $this->flashMessenger()->clearMessages();
            // falier maeesage 
            $this->flashMessenger()->addErrorMessage($failureMessage);
            // status and unsubscripeFlag
            $this->flashMessenger()->addInfoMessage($status)->addInfoMessage(0);
        }

        $url = $this->getEvent()->getRouter()->assemble(array('action' => 'index'), array('name' => 'cmsPressReleaseList'));
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

        // delete all perivious messages
        $this->flashMessenger()->clearMessages();
        // falier maeesage 
        $this->flashMessenger()->addErrorMessage($message);
        // status and unsubscripeFlag
        $this->flashMessenger()->addInfoMessage($status)->addInfoMessage(1);

        $url = $this->getEvent()->getRouter()->assemble(array('action' => 'index'), array('name' => 'cmsPressReleaseList'));
        return $this->redirect()->toUrl($url);
    }

    /**
     * a page that shows press details && send to friend from &&  
     * @return ViewModel
     */
    public function detailsAction()
    {
        $variables = array();
        $translatorHandler = $this->getServiceLocator()->get('translatorHandler');
        $query = $this->getServiceLocator()->get('wrapperQuery');
        $pressModel = $this->getServiceLocator()->get('CMS\Model\Press');

        $newsId = $this->params('id');
        $newsDetails = $pressModel->getMoreDetails($newsId);
        $newsDetails = reset($newsDetails);
        // if type is page .. so it will return null
        if (is_null($newsDetails) || $newsDetails->getStatus() === Status::STATUS_INACTIVE) {

            $url = $this->getEvent()->getRouter()->assemble(array('action' => 'resourceNotFound'), array(
                'name' => 'resource_not_found'));
            $this->redirect()->toUrl($url);
        }

        $form = new \DefaultModule\Form\ContactUsForm();
        $request = $this->getRequest();

        //checking if we got a new post request
        if ($request->isPost()) {
            $data = $request->getPost()->toArray();
            $inputFilter = $form->getInputFilter();

            // adding custom value for ignored fields
            $url = $this->getRequest()->getServer('HTTP_HOST') . $this->url()->fromRoute() . '/' . $newsId;
            $data["subject"] = $newsDetails->getTitle();
            $data["message"] = 'A friend of yours wants you to check this out ' . $url;
            $data["name"] = '';

            $form->setData($data);

            //ignored Fields
            $input = $inputFilter->get('name');
            $input->setRequired(false);

            $input = $inputFilter->get('subject');
            $input->setRequired(false);

            $input = $inputFilter->get('message');
            $input->setRequired(false);


            // checking if the form is valid
            if ($form->isValid()) {
                $sTF = $this->getServiceLocator()->get('CMS\Service\STF');
                $submissionResult = $sTF->submitMessage($data, $form);
                $variables['messages'] = $submissionResult['messages'];
                $variables['type'] = $submissionResult['type'];
            }
        }
        $variables['form'] = $this->getFormView($form);
        $variables['details'] = $newsDetails;
        return new ViewModel($variables);
    }

    public function pdfAction()
    {
        $query = $this->getServiceLocator()->get('wrapperQuery');
        $pressModel = $this->getServiceLocator()->get('CMS\Model\Press');
        $newsId = $this->params('newsId');
        $newsDetails = $pressModel->getMoreDetails($newsId)[0];

        $translatorHandler = $this->getServiceLocator()->get('translatorHandler');
        $languageFlag = $translatorHandler->getLocale() === Locale::LOCALE_AR_AR ? $translationFlag = false : $translationFlag = true;
        $this->renderer = $this->getServiceLocator()->get('ViewRenderer');
        $content = $this->renderer->render('cms/press-release/pdf', array(
            'tmp_name' => $newsDetails->picture['tmp_name'],
            'created' => $newsDetails->created,
            'title' => $newsDetails->title,
            'titleAr' => $newsDetails->titleAr,
            'body' => $newsDetails->body,
            'bodyAr' => $newsDetails->bodyAr,
            'summary' => $newsDetails->summary,
            'summaryAr' => $newsDetails->summaryAr,
            'author' => $newsDetails->author,
            'language' => $languageFlag
        ));

        $tcpdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $tcpdf->AddPage();
        $tcpdf->SetFont('aealarabiya', '', 12);
        $tcpdf->setRTL(!true);

        $html = new MimePart($content);
        $html->type = "text/html";
        $body = new MimeMessage();
        $body->setParts(array($html));
        // render html with our variables 
        $tcpdf->writeHTML($html->getContent(), true, 0, true, 0);
        // creating the output PDF  && D for download 
        $languageFlag ? $tcpdf->Output($newsDetails->title . '.pdf', 'D') : $tcpdf->Output($newsDetails->titleAr . '.pdf', 'D');
    }

}
