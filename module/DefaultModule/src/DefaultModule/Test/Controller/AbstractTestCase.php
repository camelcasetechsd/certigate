<?php

namespace DefaultModule\Test\Controller;

use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;

/**
 * AbstractTestCase Parent for each and every test case providing common needs for all test cases
 * 
 * @property Zend\ServiceManager\ServiceManager $serviceManager
 * @property Zend\Mvc\ApplicationInterface $application
 * @property Utilities\Service\Fixture\FixtureLoader $fixtureLoader
 * @property bool $traceError ,default is true
 * @property bool $firstTestCaseFlag ,default is true
 * @property string $truncateQuery
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
     * @var Utilities\Service\Fixture\FixtureLoader
     */
    public $fixtureLoader;

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
     * @var string
     */
    static private $truncateQuery;

    /**
     * Setup test case needed properties
     * 
     * @access public
     */
    public function setUp()
    {
        $this->serviceManager = \PhpunitBootstrap::getServiceManager();
        
        if (empty($this->getApplicationConfig())) {
            $this->setApplicationConfig(
                    $this->serviceManager->get('ApplicationConfig')
            );
        }
        $this->application = $this->getApplication();
        $this->fixtureLoader = $this->serviceManager->get("Utilities\Service\Fixture\FixtureLoader");
        $this->fixtureLoader->setDefaultFixtures(array(
            "Users\Fixture\Acl",
            "Users\Fixture\Role"
        ));
        // refresh DB structure
        if (self::$firstTestCaseFlag === true) {
            shell_exec("bin/doctrine orm:schema-tool:drop --force; "
                    . "bin/doctrine orm:schema-tool:update --force;");
        }
        else {
            $this->truncateDatabase();
        }

        parent::setUp();
        self::$firstTestCaseFlag = false;
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
        if (empty(self::$truncateQuery)) {
            $schemaManager = $connection->getSchemaManager();
            $tables = $schemaManager->listTables();
            $query = '';

            $query .= 'set foreign_key_checks=0;';
            foreach ($tables as $table) {
                $name = $table->getName();
                $query .= 'DELETE FROM ' . $name . ';VACUUM;';
            }
            $query .= 'set foreign_key_checks=1;';
            self::$truncateQuery = $query;
        }
        $connection->executeQuery(self::$truncateQuery);
    }

}
