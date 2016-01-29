<?php

namespace CustomMustache\Service;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Mustache\View\Renderer;
use CMS\Entity\Menu;
use CMS\Service\Cache\CacheHandler;
use Zend\Authentication\AuthenticationService;
use Users\Entity\Role;

/**
 * Renderer Factory
 * 
 * Prepare Renderer service factory
 * 
 * 
 * 
 * @package customMustache
 * @subpackage service
 */
class RendererFactory implements FactoryInterface
{

    /**
     * Prepare Renderer service
     * 
     * 
     * @uses AuthenticationService
     * @uses \Mustache_Engine
     * @uses Renderer
     * 
     * @access public
     * @param ServiceLocatorInterface $serviceLocator
     * @return Renderer
     */
    public function createService( ServiceLocatorInterface $serviceLocator )
    {

        $config = $serviceLocator->get( 'Configuration' );
        $config = $config['mustache'];

        // set isProduction according to current environment
        $config['helpers']['isProduction'] = (APPLICATION_ENV == "production" ) ? true : false;

        $auth = new AuthenticationService();
        $storage = $auth->getIdentity();

        $config['helpers']['primaryMenu'] = '';

        $forceFlush = !$config['helpers']['isProduction'];
        $cmsCacheHandler = $serviceLocator->get( 'cmsCacheHandler' );
        $menuView = $serviceLocator->get( 'cmsMenuView' );

        // TODO: Implement a better way to do this, allowing menu item hierarchy to be respected
        $path = $serviceLocator->get('request')->getUri()->getPath();
        $menuView->setActivePath($path);

        $menusArray = $cmsCacheHandler->getCachedCMSData( $forceFlush );
        $menusViewArray = $menuView->prepareMenuView( $menusArray[CacheHandler::MENUS_KEY], /* $menuTitleUnderscored = */ Menu::PRIMARY_MENU_UNDERSCORED );
        $config['helpers']['primaryMenu'] = isset( $menusViewArray[Menu::PRIMARY_MENU_UNDERSCORED] ) ? $menusViewArray[Menu::PRIMARY_MENU_UNDERSCORED] : '';


        if ($auth->hasIdentity()) {
            $roles = $storage['roles'];
        }
        if (isset( $roles ) && in_array( Role::ADMIN_ROLE, $roles )) {
            $adminMenu = $menuView->prepareMenuView( $menusArray[CacheHandler::MENUS_KEY],  Menu::ADMIN_MENU_UNDERSCORED , Menu::ADMIN_MENU_UNDERSCORED , Menu::ADMIN_MENU_UNDERSCORED);
            $config['helpers']['adminMenu'] = isset( $adminMenu[Menu::ADMIN_MENU_UNDERSCORED] ) ? $adminMenu[Menu::ADMIN_MENU_UNDERSCORED] : '';
        }   
        
        /** @var $pathResolver \Zend\View\Resolver\TemplatePathStack */
        $pathResolver = clone $serviceLocator->get( 'ViewTemplatePathStack' );
        $pathResolver->setDefaultSuffix( $config['suffix'] );

        /** @var $resolver \Zend\View\Resolver\AggregateResolver */
        $resolver = $serviceLocator->get( 'ViewResolver' );
        $resolver->attach( $pathResolver, 2 );

        $engine = new \Mustache_Engine( $this->setConfigs( $config ) );

        $renderer = new Renderer();
        $renderer->setEngine( $engine );
        $renderer->setSuffix( isset( $config['suffix'] ) ? $config['suffix'] : 'mustache'  );
        $renderer->setSuffixLocked( (bool) $config['suffixLocked'] );
        $renderer->setResolver( $resolver );

        return $renderer;
    }

    /**
     * Prepare config array
     * 
     * 
     * @uses \Mustache_Loader_FilesystemLoader
     * 
     * @access private
     * @param array $config
     * @return array configuration array for mustache
     */
    private function setConfigs( array $config )
    {
        $options = array("extension" => ".phtml");
        if (isset( $config["partials_loader"] )) {
            $path = $config["partials_loader"];
            if (is_array( $config["partials_loader"] )) {
                $path = $config["partials_loader"][0];
            }
            $config["partials_loader"] = new \Mustache_Loader_FilesystemLoader( $path, $options );
        }

        if (isset( $config["loader"] )) {
            $config["loader"] = new \Mustache_Loader_FilesystemLoader( $config["loader"][0], $options );
        }
        return $config;
    }

}
