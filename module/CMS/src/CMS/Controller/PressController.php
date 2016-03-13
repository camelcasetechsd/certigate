<?php

namespace CMS\Controller;

use Utilities\Controller\ActionController;
use Zend\View\Model\ViewModel;
use Zend\Mime\Part as MimePart;
use Zend\Mime\Message as MimeMessage;

class PressController extends ActionController
{

    public function detailsAction()
    {
        $variables = array();
        $query = $this->getServiceLocator()->get('wrapperQuery');
        $pressModel = new \CMS\Model\Press($query);

        $newsId = $this->params('id');
        $newsDetails = $pressModel->getMoreDetails($newsId);
        // if type is page .. so it will return null
        if (is_null($newsDetails)) {

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
            $url = $this->getRequest()->getServer('HTTP_HOST') . $this->url()->fromRoute().'/'.$newsId;
            $data["subject"] = \Notifications\Service\MailSubjects::SEND_TO_FRIEND;
            $data["message"] = 'A friend of yours wants you to check this out '.$url  ;
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
        $pressModel = new \CMS\Model\Press($query);
        $newsId = $this->params('newsId');
        $newsDetails = $pressModel->getMoreDetails($newsId)[0];
        $dompdf = new \DOMPDF();

        $this->renderer = $this->getServiceLocator()->get('ViewRenderer');
        $content = $this->renderer->render('cms/press/pdf', array(
            'tmp_name' => $newsDetails->picture['tmp_name'],
            'created' => $newsDetails->created,
            'title' => $newsDetails->title,
            'body' => $newsDetails->body,
            'summary' => $newsDetails->summary,
            'author' => $newsDetails->author
        ));
        // make a header as html  
        $html = new MimePart($content);
        $html->type = "text/html";
        $body = new MimeMessage("Hello");
        $body->setParts(array($html));

        // add html to convert it to pdf
        $dompdf->load_html($html->getContent());
        $dompdf->set_paper("letter", "portrait");
        $dompdf->render();
        //download popup
        $dompdf->stream($newsDetails->title . '.pdf');
    }

}
