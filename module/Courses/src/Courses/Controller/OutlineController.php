<?php

namespace Courses\Controller;

use Utilities\Controller\ActionController;
use Zend\View\Model\ViewModel;
use Zend\Mime\Part as MimePart;
use Zend\Mime\Message as MimeMessage;
use TCPDF;

/**
 * Outline Controller
 * 
 * outlines entries listing
 * 
 * 
 * 
 * @package courses
 * @subpackage controller
 */
class OutlineController extends ActionController
{

    /**
     * List outlines
     * 
     * 
     * @access public
     * 
     * @return ViewModel
     */
    public function indexAction()
    {
        $variables = array();
        $courseId = $this->params('courseId');
        $query = $this->getServiceLocator()->get('wrapperQuery');
        $course = $query->find(/* $entityName = */'Courses\Entity\Course', $courseId);
        // if course does not exist
        if ($course == null) {
            $this->getResponse()->setStatusCode(302);
            $url = $this->getEvent()->getRouter()->assemble(array(), array('name' => 'resource_not_found'));
            $this->redirect()->toUrl($url);
        }
        else {
            $data = $course->getOutlines();
            $variables['outlines'] = $data;
            return new ViewModel($variables);
        }
    }

    /**
     * List outlines
     * 
     * 
     * @access public
     * 
     * @return ViewModel
     */
    public function generatePdfAction()
    {
        $courseId = $this->params('id');
        $query = $this->getServiceLocator()->get('wrapperQuery');
        $objectUtilities = $this->getServiceLocator()->get('objectUtilities');
        $course = $query->find(/* $entityName = */'Courses\Entity\Course', $courseId);
        // if course does not exist
        if (!is_object($course)) {
            $this->getResponse()->setStatusCode(302);
            $url = $this->getEvent()->getRouter()->assemble(array(), array('name' => 'resource_not_found'));
            return $this->redirect()->toUrl($url);
        }

        $tcpdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $tcpdf->AddPage();
        $tcpdf->SetFont('aealarabiya', '', 12);

        $this->renderer = $this->getServiceLocator()->get('ViewRenderer');
        $content = $this->renderer->render('courses/outline/generate-pdf', array(
            'outlines' => $objectUtilities->prepareForDisplay($course->getOutlines())
        ));
        $html = new MimePart($content);
        $html->type = "text/html";
        $body = new MimeMessage();
        $body->setParts(array($html));
        // render html with our variables 
        $tcpdf->writeHTML($html->getContent(), true, 0, true, 0);
        // creating the output PDF  && D for download 
        $tcpdf->Output( "{$course->getName()} outlines.pdf", 'D');
    }

}
