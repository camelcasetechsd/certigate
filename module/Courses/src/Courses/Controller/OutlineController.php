<?php

namespace Courses\Controller;

use Utilities\Controller\ActionController;
use Zend\View\Model\ViewModel;
use DOMPDFModule\View\Model\PdfModel;

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
        $pdfModel = new PdfModel();
        $pdfModel->setOption("filename", "{$course->getName()} outlines"); // Triggers PDF download, automatically appends ".pdf"
        // To set view variables
        $pdfModel->setVariables(array(
          'outlines' => $objectUtilities->prepareForDisplay($course->getOutlines())
        ));
        $pdfModel->setTemplate("courses/outline/generate-pdf");
        return $this->getPdfView($pdfModel);
    }

}
