<?php

namespace Translation\Helper;

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
class TranslatorHelperFactory implements FactoryInterface
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
        $translatorHelper = new TranslatorHelper();
        $translatorHelper->setServiceLocator($serviceLocator);
        return $translatorHelper;
    }

}
