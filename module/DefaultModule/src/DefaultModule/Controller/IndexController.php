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
        $fileName = $this->params('filename');
        $path = "/public/upload/general_resorces/";
        $response = new \Zend\Http\Response\Stream();
        $response->setStream(fopen($path.$fileName, 'r'));
        $response->setStatusCode(200);
        $response->setStreamName(basename($path.$fileName));
        $headers = new \Zend\Http\Headers();
        $headers->addHeaders(array(
            'Content-Disposition' => 'attachment; filename="' . basename($path.$fileName) . '"',
            'Content-Type' => 'application/octet-stream',
            'Content-Length' => filesize($path.$fileName),
            'Expires' => '@0', // @0, because zf2 parses date as string to \DateTime() object
            'Cache-Control' => 'must-revalidate',
            'Pragma' => 'public'
        ));
        $response->setHeaders($headers);
        return $response;
    }

}
