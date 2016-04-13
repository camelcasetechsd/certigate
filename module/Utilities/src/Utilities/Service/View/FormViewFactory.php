<?php

namespace Utilities\Service\View;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Utilities\Service\View\FormView;

/**
 * FormView Factory
 * 
 * Prepare FormView service factory
 * 
 * 
 * 
 * @package utilities
 * @subpackage service
 */
class FormViewFactory implements FactoryInterface {

    /**
     * Prepare FormView service
     * 
     * 
     * @uses FormView
     * 
     * @access public
     * @param ServiceLocatorInterface $serviceLocator
     * @return FormView
     */
    public function createService(ServiceLocatorInterface $serviceLocator) {
        $viewRenderer = $serviceLocator->get('ViewRenderer');
        $formView = new FormView($viewRenderer);
        return $formView;
    }

}
