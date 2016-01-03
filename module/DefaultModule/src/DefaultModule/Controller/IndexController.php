<?php

namespace DefaultModule\Controller;

use Utilities\Controller\ActionController;
use Zend\View\Model\ViewModel;

/**
 * Index Controller
 * 
 * Handles Application homepage
 * 
 * 
 * 
 * @package defaultModule
 * @subpackage controller
 */
class IndexController extends ActionController
{

    /**
     * Application homepage
     * 
     * 
     * @access public
     * @return ViewModel
     */
    public function indexAction()
    {
        return new ViewModel();
    }


}

