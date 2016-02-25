<?php

namespace Translation\Controller;

use Zend\View\Model\ViewModel;

class IndexController extends \Utilities\Controller\ActionController
{

    public function indexAction()
    {
        $translator = $this->getServiceLocator()->get('translatorHandler');
        $translator->setLocale('ar_Ar');
        
        var_dump($translator);exit;
        return new ViewModel();
    }

}
