<?php

namespace CMS\Event;

use Zend\Mvc\MvcEvent;
use Zend\Console\Request;

/**
 * RouteEvent Model
 * 
 * Handles RouteEvent related business
 * 
 * 
 * @property array $staticPagePaths
 * 
 * @package cms
 * @subpackage event
 */
class RouteEvent
{

    /**
     *
     * @var array
     */
    protected $staticPagePaths;

    /**
     * Set needed properties
     * 
     * @access public
     * @param array $staticPagePaths
     */
    public function __construct($staticPagePaths)
    {
        $this->staticPagePaths = $staticPagePaths;
    }

    /**
     * addStaticPagesRoutes Event Handler
     * set static pages routes
     * 
     * @access public
     * @param MvcEvent $event
     * @return array route config
     */
    public function addStaticPagesRoutes(MvcEvent $event)
    {
        // get the router
        $router = $event->getRouter();

        $staticPagePathsString = "(" . implode("|", $this->staticPagePaths) . ")";
        // pull static pages routing data from your database,
        // create route
        $routeName = "cmsPageView";
        $routeConfig = array($routeName => array(
                'type' => 'Zend\Mvc\Router\Http\Regex',
                'options' => array(
                    'regex' => $staticPagePathsString,
                    'spec' => "%path%",
                    'defaults' => array(
                        'module' => "CMS",
                        'controller' => "CMS\Controller\Page",
                        'action' => "view"
        ))));
        // if not console route
        if (! $event->getRequest() instanceof Request) {
            // add it to the router
            $router->addRoute($routeName, $routeConfig[$routeName]);
        }
        return $routeConfig;
    }

}
