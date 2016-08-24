<?php

	namespace CMS\Entity;
	
	use Zend\ServiceManager\FactoryInterface;
	use Zend\ServiceManager\ServiceLocatorInterface;
	use CMS\Entity\MenuItemRepository;
	
	/**
	 * MenuItem Factory
	 * 
	 * Prepare MenuItem service factory
	 * 
	 * 
	 * @package cms
	 * @subpackage model
	 */
	class MenuItemRepositoryFactory implements FactoryInterface {
	
	    /**
	     * Prepare MenuItem service
	     * 
	     * @uses MenuItem
	     * 
	     * @access public
	     * @param ServiceLocatorInterface $serviceLocator
	     * @return MenuItem
	     */
	    public function createService(ServiceLocatorInterface $serviceLocator) {
	        
	        $query = $serviceLocator->get('wrapperQuery')->setEntity(/* $entityName = */ 'CMS\Entity\MenuItem');
	        $menuItemRepository = $this->query->setEntity(/* $entityName = */ 'CMS\Entity\MenuItem')->entityRepository;
	        $menuItemRepository->setServiceLocator($this->getServiceLocator());
	        return $menuItemRepository;
	    }
	
	}
