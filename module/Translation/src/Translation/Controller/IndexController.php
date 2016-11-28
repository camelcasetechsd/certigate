<?php

namespace Translation\Controller;

use Zend\View\Model\ViewModel;

class IndexController extends \Utilities\Controller\ActionController
{

    public function indexAction()
    {
        return new ViewModel();
    }

    public function setLocaleAction()
    {
        $locale = $this->params('locale');
        
        /* @var $cmsCacheHandler \CMS\Service\Cache\CacheHandler */
        $cmsCacheHandler = $this->getServiceLocator()->get('cmsCacheHandler');
        $cmsCacheHandler->flushCMSCache(\CMS\Service\Cache\CacheHandler::MENUS_KEY);
        
        /* @var $applicationLocale \Translation\Service\Locale\Locale */
        $applicationLocale = $this->getServiceLocator()->get('applicationLocale');
        $applicationLocale->setCurrentLocale($locale);
        $applicationLocale->saveLocaleInCookies();
                
        $url = ($this->getRequest()->getHeader('Referer')->getUri()) ?:  '/';
        
        $this->redirect()->toUrl($url);
    }
}
