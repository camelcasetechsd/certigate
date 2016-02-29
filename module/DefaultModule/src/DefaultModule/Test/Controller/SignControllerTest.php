<?php

use DefaultModule\Test\Controller\AbstractTestCase;
use Zend\Http\Request;
use Users\Fixture\UserCredentials;

/**
 * SignControllerTest Tests for SignController
 * 
 * @package defaultModule
 * @subpackage test
 */
class SignControllerTest extends AbstractTestCase
{

    /**
     * Tests inAction with valid credentials submitted
     * 
     * @access public
     * 
     * @covers SignController::inAction()
     */
    public function testInAction()
    {
        $this->fixtureLoader->loadFixturesFromEntities(array("Users\Entity\User"));
        $userCredentials = UserCredentials::$userCredentials;
        $randomUsername = array_rand($userCredentials);
        
        // sign in with random user credentials
        $this->dispatch("/sign/in", Request::METHOD_POST, array("username" => $randomUsername, "password" => $userCredentials[$randomUsername]["password"]));

        // Assert response is a redirect
        $this->assertRedirect();
        
        // Assert redirect to home page
        $this->assertRedirectTo('/');
    }

}
