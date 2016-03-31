<?php

namespace DefaultModule\Controller;

use Utilities\Controller\ActionController;
use Zend\View\Model\ViewModel;
use DefaultModule\Form\ContactUsForm;

/**
 * ContactUs Controller
 * 
 * Handles Application ContactUs
 * 
 * 
 * 
 * @package defaultModule
 * @subpackage controller
 */
class ContactUsController extends ActionController
{

    /**
     * Application contactUs
     * 
     * @uses ContactUsForm
     * 
     * @access public
     * @return ViewModel
     */
    public function indexAction()
    {
        $variables = array();
        $form = new ContactUsForm();
        $request = $this->getRequest();

        //checking if we got a new post request
        if ($request->isPost()) {
            $data = $request->getPost()->toArray();
            $form->setData($data);
            // checking if the form is valid
            if ($form->isValid()) {
                $contactUs = $this->getServiceLocator()->get('DefaultModule\Service\ContactUs');
                $submissionResult = $contactUs->submitMessage($data, $form);
                $variables['messages'] = $submissionResult['messages'];
            }
        }
        $variables['form'] = $this->getFormView($form);
        return new ViewModel($variables);
    }

}
