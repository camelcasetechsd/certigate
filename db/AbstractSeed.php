<?php

namespace db;

require_once 'init_autoloader.php';

use Phinx\Seed\AbstractSeed as OriginalAbstractSeed;
use Zend\Mvc\Application;

/**
 * Abstract Seed Class.
 *
 * It is expected that the seeds you write extend from this class.
 * 
 * @property Zend\ServiceManager\ServiceManager $serviceManager
 *
 */
class AbstractSeed extends OriginalAbstractSeed
{
    /**
     *
     * @var Zend\ServiceManager\ServiceManager 
     */
    protected $serviceManager;
    
    /**
     * Initialize method.
     *
     * @access protected
     * @return void
     */
    protected function init()
    {
        // Run the application!
        $application = Application::init( require __DIR__.'/../config/application.config.php' );
        $this->serviceManager = $application->getServiceManager();
    }
}
