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

}

