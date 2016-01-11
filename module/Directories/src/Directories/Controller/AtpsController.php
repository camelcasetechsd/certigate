<?php

namespace Directories\Controller;

use Zend\View\Model\ViewModel;
use Utilities\Controller\ActionController;
use Directories\Form\AtpForm;

/**
 * Atps Controller
 * 
 * Atps entries listing
 * 
 * 
 * 
 * @package directories
 * @subpackage controller
 */
class AtpsController extends ActionController
{

    /**
     * List ATPs
     * 
     * 
     * @access public
     * 
     * @return ViewModel
     */
    public function indexAction()
    {
        return new ViewModel();
    }

    /**
     * more details of an ATP
     * 
     * 
     * @access public
     * 
     * @return ViewModel
     */
    public function moreAction()
    {
        return new ViewModel();
    }

    /**
     * create new ATP
     * 
     * 
     * @access public
     * 
     * @return ViewModel
     */
    public function newAction()
    {
        $form = new AtpForm();

        $variables['atpForm'] = $this->getFormView($form);
        return new ViewModel($variables);
    }

}
