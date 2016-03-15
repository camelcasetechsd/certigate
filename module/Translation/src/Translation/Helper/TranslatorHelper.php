<?php

namespace Translation\Helper;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Mustache Helper 
 */
class TranslatorHelper implements ServiceLocatorAwareInterface
{

    /**
     *
     * @var \Zend\ServiceManager\ServiceLocatorInterface
     */
    protected $serviceLocator;

    /**
     * Called when class is called as a method
     * 
     * @access public
     * @param string $text actual text in mustache template as "{{param}}" for instance
     * @param object $mustache
     * @return string the processed output to replace the input passed to the method
     */
    public function __invoke($text, $mustache)
    {
        /* @var $translatorHandler \Translation\Service\Translator\TranslatorHandler */
        $translatorHandler = $this->getServiceLocator()->get('translatorHandler');
        return $translatorHandler->translate($mustache->render($text));
    }

    public function getServiceLocator()
    {
        return $this->serviceLocator;
    }

    public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
    }

}
