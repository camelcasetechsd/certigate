<?php

namespace Translation\Helper;

use Zend\ServiceManager\ServiceManagerAwareInterface;
use Zend\ServiceManager\ServiceManager;

/**
 * Mustache Helper 
 */
class TranslatorHelper implements ServiceManagerAwareInterface
{

    /**
     *
     * @var \Zend\ServiceManager\ServiceManager
     */
    protected $serviceManager;

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
        $translatorHandler = $this->getServiceManager()->get('translatorHandler');
        return $translatorHandler->translate($mustache->render($text));
    }

    public function setServiceManager(ServiceManager $serviceManager)
    {
        $this->serviceManager = $serviceManager;
    }

    public function getServiceManager()
    {
        return $this->serviceManager;
    }
}
