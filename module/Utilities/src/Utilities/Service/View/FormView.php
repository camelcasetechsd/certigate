<?php

namespace Utilities\Service\View;

use Utilities\Form\FormViewHelper;
use Zend\Form\FormInterface;
use Zend\Form\View\Helper\Form;

/**
 * FormView
 * 
 * Prepare form view
 * 
 * @property FormViewHelper $formViewHelper
 * @property Zend\View\Renderer\RendererInterface $viewRenderer
 * 
 * @package utilities
 * @subpackage service
 */
class FormView
{

    /**
     *
     * @var FormViewHelper 
     */
    protected $formViewHelper;
    
    /**
     *
     * @var Zend\View\Renderer\RendererInterface
     */
    public $viewRenderer;

    /**
     * Set needed properties
     * 
     * 
     * @access public
     * @param Zend\View\Renderer\RendererInterface $viewRenderer
     */
    public function __construct($viewRenderer)
    {
        $this->viewRenderer = $viewRenderer;
    }

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
        $formHelper->setView($this->viewRenderer);
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
        if (!$this->formViewHelper instanceof Form) {
            $this->formViewHelper = new FormViewHelper();
        }
        return $this->formViewHelper;
    }
}
