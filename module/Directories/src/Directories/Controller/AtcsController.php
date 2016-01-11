<?php

namespace Directories\Controller;

use Zend\View\Model\ViewModel;
use Utilities\Controller\ActionController;
use Directories\Form\AtcForm;

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
class AtcsController extends ActionController
{

    /**
     * List ATCs
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
     * more details of an ATC
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
     * create new ATC
     * 
     * 
     * @access public
     * 
     * @return ViewModel
     */
    public function newAction()
    {
        $form = new AtcForm();
        
        $variables['atcForm'] = $this->getFormView($form);
        return new ViewModel($variables);
    }

}
