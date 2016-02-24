<?php

namespace DefaultModule\Test\Controller;

use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;
use Doctrine\Common\DataFixtures\Loader;
use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;

/**
 * AbstractTestCase Parent for each and every test case providing common needs for all test cases
 * 
 * @property Zend\ServiceManager\ServiceManager $serviceManager
 * @property Zend\Mvc\ApplicationInterface $application
 * @property Doctrine\Common\DataFixtures\Loader $loader
 * @property bool $traceError ,default is true
 * @property bool $firstTestCaseFlag ,default is true
 * 
 * @package defaultModule
 * @subpackage test
 */
abstract class AbstractTestCase extends AbstractHttpControllerTestCase
{

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
     * @var Doctrine\Common\DataFixtures\Loader
     */
    public $loader;

    /**
     *
     * @var bool 
     */
    protected $traceError = true;

    /**
     * @var bool
     */
    static private $firstTestCaseFlag = true;

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
        $this->application = $this->getApplication();
        $this->serviceManager = $this->getApplicationServiceLocator();
        $this->loader = new Loader();

        // refresh DB structure
        if (self::$firstTestCaseFlag === true) {
            shell_exec("bin/doctrine orm:schema-tool:drop --force;");
            shell_exec("bin/doctrine orm:schema-tool:update --force;");
        }
        else {
            $this->truncateDatabase();
        }

        parent::setUp();
        self::$firstTestCaseFlag = false;
    }

    /**
     * Load fixtures in database
     * 
     * @access public
     * @param array $fixtures array of fixture classes
     */
    public function loadFixtures($fixtures)
    {
        foreach ($fixtures as $fixture) {
            $this->loader->addFixture($fixture);
        }
        $purger = new ORMPurger();
        $entityManager = $this->serviceManager->get('doctrine.entitymanager.orm_default');
        $executor = new ORMExecutor($entityManager, $purger);
        $executor->execute($this->loader->getFixtures());
    }

    /**
     * Truncate all tables in database
     * 
     * @access public
     */
    public function truncateDatabase()
    {
        $entityManager = $this->serviceManager->get('doctrine.entitymanager.orm_default');
        $connection = $entityManager->getConnection();
        $schemaManager = $connection->getSchemaManager();
        $tables = $schemaManager->listTables();
        $query = '';

        $query .= 'set foreign_key_checks=0;';
        foreach ($tables as $table) {
            $name = $table->getName();
            $query .= 'TRUNCATE ' . $name . ';';
        }
        $query .= 'set foreign_key_checks=1;';
    }

}
