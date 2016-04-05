<?php

namespace Courses\Model;


/**
 * QuoteGenerator Model
 * 
 * Handles Quote model generation
 * 
 * 
 * @property Zend\ServiceManager\ServiceLocatorInterface $serviceLocator
 * 
 * @package courses
 * @subpackage model
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
