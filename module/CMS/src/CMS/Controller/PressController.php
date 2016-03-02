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
        $pressModel = new \CMS\Model\Press($query);

        $newsId = $this->params('id');
        $newsDetails = $pressModel->getMoreDetails($newsId);
        // if type is page .. so it will return null
        if (is_null($newsDetails)) {
            
            $url = $this->getEvent()->getRouter()->assemble(array('action' => 'resourceNotFound'), array(
                'name' => 'resource_not_found'));
            $this->redirect()->toUrl($url);
        }
        $variables['details'] = $newsDetails[0];
        return new ViewModel($variables);
    }

}
