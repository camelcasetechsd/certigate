<?php

namespace CMS\Controller;

use Utilities\Controller\ActionController;
use Zend\View\Model\ViewModel;
use CMS\Form\PageForm;
use CMS\Entity\Page;
use Utilities\Service\Status;

class PressController extends ActionController
{

    public function detailsAction()
    {
        $variables = array();
        $query = $this->getServiceLocator()->get('wrapperQuery');
        $pressModel = $this->getServiceLocator()->get('CMS\Model\Press');

        $newsId = $this->params('id');
        $newsDetails = $pressModel->getMoreDetails($newsId);
        
        $variables['details'] = $newsDetails;
        return new ViewModel($variables);
    }

}
