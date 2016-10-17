<?php

namespace System\Controller;

use Utilities\Controller\ActionController;
use Zend\View\Model\ViewModel;

/**
 * Cache Controller
 *
 * Manually Flush Cache Controller
 *
 * @package system
 * @subpackage controller
 */
class CacheController extends ActionController
{

    public function indexAction()
    {
        $variables = [
            'cache_types' => [
                [
                    'key' => \CMS\Service\Cache\CacheHandler::MENUS_KEY,
                    'name' => 'Menus'
                ],
                [
                    'key' => \CMS\Service\Cache\CacheHandler::MENUS_PATHS_KEY,
                    'name' => 'Menus Pathes ( Pages )'
                ],
                [
                    'key' => \System\Service\Cache\CacheHandler::SETTINGS_KEY,
                    'name' => 'Settings'
                ],
            ]
        ];

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost()->toArray();
            $cacheTypes = $data['cache_types'];
            
            /* @var $cacheHandler \Utilities\Service\Cache */
            $cacheHandler = $this->getServiceLocator()->get('cacheUtilities');
            $cacheHandler->flush($cacheTypes);
                    
            $url = $this->getEvent()->getRouter()->assemble(array('action' => 'index'), array('name' => 'systemSettings'));
            $this->redirect()->toUrl($url);
        }


        return new ViewModel($variables);
    }

}
