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

    public function generalResourcesAction()
    {
        return new ViewModel();
    }

    public function downloadAction()
    {
        $fileUtilities = $this->getServiceLocator()->get('fileUtilities');
        $path = "/public/upload/general_resorces/";
        $fileName = $this->params('filename');
        return $fileUtilities->getFileResponse(/*$file =*/ $path.$fileName);
    }

}
