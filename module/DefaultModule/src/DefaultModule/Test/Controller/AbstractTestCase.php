<?php

namespace DefaultModule\Test\Controller;

use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;
use PHPUnit_Extensions_Database_TestCase_Trait;
use PHPUnit_Extensions_Database_DataSet_YamlDataSet;

/**
 * AbstractTestCase Parent for each and every test case providing common needs for all test cases
 * 
 * @property Zend\ServiceManager\ServiceManager $serviceManager
 * @property Zend\Mvc\ApplicationInterface $application
 * @property bool $traceError ,default is true
 * @property Doctrine\DBAL\Connection $connection ,default is null
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
     * only instantiate PHPUnit_Extensions_Database_DB_IDatabaseConnection once per test
     * @var Doctrine\DBAL\Connection 
     */
    private $connection = null;

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

        $entitymanager = $this->serviceManager->get('doctrine.entitymanager.orm_default');
        $connection = $entitymanager->getConnection();
        $connection->executeQuery("set foreign_key_checks=0");
        parent::setUp();

        // this part is a duplicate from original trait setup method, as it is overridden here 
        $this->databaseTester = NULL;
        $this->getDatabaseTester()->setSetUpOperation($this->getSetUpOperation());
        $this->getDatabaseTester()->setDataSet($this->getDataSet());
        $this->getDatabaseTester()->onSetUp();

        $connection->executeQuery("set foreign_key_checks=1");
    }

    /**
     * Get connection using doctrine entity manager
     * 
     * @access public
     * @return Doctrine\DBAL\Connection 
     */
    final public function getConnection()
    {
        if ($this->connection === null) {
            $entitymanager = $this->serviceManager->get('doctrine.entitymanager.orm_default');
            $connection = $entitymanager->getConnection();
            $pdo = $connection->getWrappedConnection();
            $databaseName = $connection->getDatabase();

            $this->connection = $this->createDefaultDBConnection($pdo, $databaseName);
        }

        return $this->connection;
    }

    /**
     * @return PHPUnit_Extensions_Database_DataSet_IDataSet
     */
    public function getDataSet()
    {
        return $this->createMySQLXMLDataSet('extra/sql.xml');
    }

}
