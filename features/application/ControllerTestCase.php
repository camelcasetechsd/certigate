<?php

require 'init_autoloader.php';

use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;
use Zend\Mvc\Application;

class ControllerTestCase extends AbstractHttpControllerTestCase
{

    public $application;

    public function setUp()
    {
        $this->application = Application::init(require 'config/application.config.php');
        parent::setUp();
    }

}
