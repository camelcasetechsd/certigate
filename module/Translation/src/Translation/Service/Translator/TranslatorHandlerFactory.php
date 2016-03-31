<?php

namespace Translation\Service\Translator;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Translation\Service\Translator\TranslatorHandler;

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
class TranslatorHandlerFactory implements FactoryInterface
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
        $translatorHandler = new TranslatorHandler();
        
        /* @var $applicationLocale \Translation\Service\Locale\Locale */
        $applicationLocale = $serviceLocator->get('applicationLocale');
        $applicationLocale->loadLocaleFromCookies();
        $currentLocale = $applicationLocale->getCurrentLocale();
        
        $translatorHandler->setApplicationLocale($applicationLocale);
        $translatorHandler->addTranslationFile('gettext', __DIR__ . '/../../../../language/'.$currentLocale.'.mo');
        
        return $translatorHandler;
    }

}
