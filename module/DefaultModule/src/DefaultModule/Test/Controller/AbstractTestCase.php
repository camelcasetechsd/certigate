<?php

namespace DefaultModule\Test\Controller;

use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;

/**
 * AbstractTestCase Parent for each and every test case providing common needs for all test cases
 * 
 * @property Zend\ServiceManager\ServiceManager $serviceManager
 * @property Zend\Mvc\ApplicationInterface $application
 * @property bool $traceError ,default is true
 * 
 * @package defaultModule
 * @subpackage test
 */
abstract class AbstractTestCase extends AbstractHttpControllerTestCase
{

    /**
     * Supports DB testing via dbunit
     */
    use PHPUnit_Extensions_Database_TestCase_Trait;

    /**
     *
     * @var Zend\Mvc\ApplicationInterface
     */
    public $application;

    /**
     *
     * @var Zend\ServiceManager\ServiceManager 
     */
    public $serviceManager;

    /**
     *
     * @var bool 
     */
    protected $traceError = true;

    /**
     * Setup test case needed properties
     * 
     * @access public
     */
    public function setUp()
    {
        $this->setApplicationConfig(
                include 'config/application.config.php'
        );
        $this->getConnection()->getConnection()->query("set foreign_key_checks=0");
        parent::setUp();
        $this->getConnection()->getConnection()->query("set foreign_key_checks=1");
        $this->application = $this->getApplication();
        $this->serviceManager = $this->getApplicationServiceLocator();
    }

}
