<?php

namespace CMS\Controller;

use Utilities\Controller\ActionController;
use Zend\View\Model\ViewModel;

/**
 * PressReleaseController Controller
 * 
 * PressRelease entries listing
 * 
 * 
 * 
 * @package cms
 * @subpackage controller
 */
class PressReleaseController extends ActionController
{

    /**
     * List press releases
     * 
     * 
     * @access public
     * 
     * @return ViewModel
     */
    public function indexAction()
    {
        $variables = array();
        $objectUtilities = $this->getServiceLocator()->get('objectUtilities');
        $pressReleaseModel = $this->getServiceLocator()->get('CMS\Model\PressRelease');
        
        $pageNumber = $this->getRequest()->getQuery('page');
        $pressReleaseModel->filterPressReleases();
        $pressReleaseModel->setPage($pageNumber);

        $pageNumbers = $pressReleaseModel->getPagesRange($pageNumber);
        $nextPageNumber = $pressReleaseModel->getNextPageNumber($pageNumber);
        $previousPageNumber = $pressReleaseModel->getPreviousPageNumber($pageNumber);
        
        $variables['pressReleases'] = $objectUtilities->prepareForDisplay($pressReleaseModel->getCurrentItems());
        $variables['pageNumbers'] = $pageNumbers;
        $variables['hasPages'] = ( count($pageNumbers) > 0 )? true : false;
        $variables['nextPageNumber'] = $nextPageNumber;
        $variables['previousPageNumber'] = $previousPageNumber;
        return new ViewModel($variables);
    }
}

