<?php

namespace Courses\Service;


/**
 * QuoteGenerator Service
 * 
 * Handles Quote model generation
 * 
 * 
 * @property Zend\ServiceManager\ServiceLocatorInterface $serviceLocator
 * 
 * @package courses
 * @subpackage service
 */
class QuoteGenerator
{

    /**
     *
     * @var Zend\ServiceManager\ServiceLocatorInterface
     */
    protected $serviceLocator;

    /**
     * Set needed properties
     * 
     * @access public
     * @param Zend\ServiceManager\ServiceLocatorInterface $serviceLocator
     */
    public function __construct($serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
    }

    /**
     * Get related quote model
     * 
     * @access public
     * @param string $type
     */
    public function getModel($type)
    {
        return $this->serviceLocator->get("Courses\Model\\{$type}Quote");
    }

}
