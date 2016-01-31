<?php

namespace DefaultModule\Controller;

use Utilities\Controller\ActionController;
use Zend\View\Model\ViewModel;

/**
 * Error Controller
 * 
 * 
 * 
 * @package defaultModule
 * @subpackage controller
 */
class ErrorController extends ActionController
{

    /**
     * No Access Action
     * 
     * 
     * @access public
     * @return ViewModel
     */
    public function noaccessAction()
    {
        return new ViewModel();
    }

    public function resourceNotFoundAction()
    {
        return new ViewModel();
    }

}
