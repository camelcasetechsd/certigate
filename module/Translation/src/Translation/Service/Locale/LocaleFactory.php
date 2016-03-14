<?php

namespace Translation\Service\Locale;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Translation\Service\Locale\Locale;

/**
 * TranslatorHandler Factory
 * 
 * Prepare TranslatorHandler service factory
 * 
 * 
 * 
 * @package cms
 * @subpackage cache
 */
class LocaleFactory implements FactoryInterface
{

    /**
     * Prepare TranslatorHandler service
     * 
     * 
     * @uses TranslatorHandler
     * 
     * @access public
     * @param ServiceLocatorInterface $serviceLocator
     * @return TranslatorHandler
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $locale = new Locale();
        return $locale;
    }

}
