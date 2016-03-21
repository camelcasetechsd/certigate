<?php

namespace Utilities\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Utilities\Form\FormViewHelper;
use Zend\Form\FormInterface;
use Zend\Form\View\Helper\Form;

/**
 * Action Controller
 * 
 * Being extended by all controllers, 
 * It provides helpers for all controllers
 * And control how all controllers behave
 * 
 * @property Form $formViewHelper
 * 
 * @package utilities
 * @subpackage controller
 */
class ActionController extends AbstractActionController
{
    
    protected $formViewHelper;
    
    /**
     * Get form HTML content
     * 
     * 
     * @access public
     * 
     * @param FormInterface $form
     * @return string form HTML content
     */
    public function getFormView(FormInterface $form)
    {
        $formHelper = $this->getFormViewHelper();
        $view = $this->getServiceLocator()->get('ViewRenderer');
        $formHelper->setView($view);
        return $formHelper->render($form);
    }
    
    /**
     * Set form view helper
     * 
     * 
     * @access public
     * 
     * @param Form $formViewHelper
     */
    public function setFormViewHelper(Form $formViewHelper)
    {
        $this->formViewHelper = $formViewHelper;
    }
    
    /**
     * Get form view helper
     * 
     * 
     * @access public
     * @uses FormViewHelper
     * 
     * @return Form form view helper
     */
    public function getFormViewHelper()
    {
        if(!$this->formViewHelper instanceof Form){
            $this->formViewHelper = new FormViewHelper();
        }
        return $this->formViewHelper;
    }
    
    /**
     * Get pdf view
     * 
     * 
     * @access public
     * @param PdfModel $pdfModel
     * 
     * @return HttpResponse pdf response
     */
    public function getPdfView($pdfModel)
    {
        $viewPdfRenderer = $this->getServiceLocator()->get('ViewPdfRenderer');
        $mustacheRenderer = $this->getServiceLocator()->get('Mustache\View\Renderer');
        $renderedPdf = $viewPdfRenderer->setHtmlRenderer($mustacheRenderer)->render($pdfModel);
        $this->getResponse()->getHeaders()->addHeaderLine('content-type', 'application/pdf');
        $fileName = $pdfModel->getOption('filename');
        if (isset($fileName)) {
            if (substr($fileName, -4) != '.pdf') {
                $fileName .= '.pdf';
            }
            
            $this->getResponse()->getHeaders()->addHeaderLine(
            	'Content-Disposition', 
            	'attachment; filename=' . $fileName);
        }
        
        return $this->getResponse()->setContent($renderedPdf);
    }

}

