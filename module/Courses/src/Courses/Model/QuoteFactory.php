<?php

namespace Courses\Model;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Courses\Model\Quote;

/**
 * Quote Factory
 * 
 * Prepare Quote service factory
 * 
 * 
 * @package courses
 * @subpackage model
 */
class QuoteFactory implements FactoryInterface
{

    /**
     * Prepare Quote service
     * 
     * @uses Quote
     * 
     * @access public
     * @param ServiceLocatorInterface $serviceLocator
     * @return Quote
     */
    public function createService(ServiceLocatorInterface $serviceLocator) {
        $query = $serviceLocator->get('wrapperQuery');
        $translationHandler = $serviceLocator->get('translatorHandler');
        $formView = $serviceLocator->get('Utilities\Service\View\FormView');
        $quoteGenerator = $serviceLocator->get('Courses\Service\QuoteGenerator');
        $config = $serviceLocator->get('Config');
        $systemCacheHandler = $serviceLocator->get('systemCacheHandler');
        $notification = $serviceLocator->get('Notifications\Service\Notification');
        $courseEventModel = $serviceLocator->get('Courses\Model\CourseEvent');
        $objectUtilities = $serviceLocator->get('objectUtilities');
        $applicationLocale = $serviceLocator->get('applicationLocale');
        return new Quote($query, $translationHandler, $formView, $quoteGenerator, $config["quote"], $systemCacheHandler, $notification, $courseEventModel, $objectUtilities, $applicationLocale);
    }

}
