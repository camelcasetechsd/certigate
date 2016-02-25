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
        return $translatorHandler;
    }

}
