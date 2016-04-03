<?php

namespace Notifications\Service\Worker;

use SlmQueue\Strategy\StrategyPluginManager;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use SlmQueue\Factory\WorkerFactory;
use Notifications\Service\Worker\DoctrineWorker;

/**
 * DoctrineWorker Factory
 * 
 * Prepare Doctrine worker factory
 * 
 * 
 * 
 * @package notifications
 * @subpackage service
 */
class DoctrineWorkerFactory extends WorkerFactory implements FactoryInterface
{
    /**
     * Create DoctrineWorker service
     *
     * @access public
     * 
     * @use DoctrineWorker
     * @param  ServiceLocatorInterface $serviceLocator
     * @return DoctrineWorker
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $config                = $serviceLocator->get('Config');
        $strategies            = $config['slm_queue']['worker_strategies']['default'];

        $eventManager          = $serviceLocator->get('EventManager');
        $listenerPluginManager = $serviceLocator->get(StrategyPluginManager::class);
        $this->attachWorkerListeners($eventManager, $listenerPluginManager, $strategies);

        return new DoctrineWorker($eventManager);
    }
}
