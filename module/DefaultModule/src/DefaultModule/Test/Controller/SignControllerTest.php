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
     * Tests inAction display
     * 
     * @access public
     * 
     * @covers SignController::inAction()
     */
    public function testInActionDisplay()
    {
        // sign in with random user credentials
        $this->dispatch("/sign/in", Request::METHOD_GET);

        // Assert login page has username field
        $this->assertQueryCount(/*$path =*/'input[name="username"]' ,/*$count =*/1);
        // Assert login page has username field
        $this->assertQueryCount(/*$path =*/'input[name="password"]' ,/*$count =*/1);
    }
    
    /**
     * Tests inAction with valid credentials submitted
     * 
     * @access public
     * 
     * @covers SignController::inAction()
     */
    public function testInActionSubmissionWithValidCredentials()
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
    
    /**
     * Tests inAction with invalid credentials submitted
     * 
     * @access public
     * 
     * @covers SignController::inAction()
     */
    public function testInActionSubmissionWithInvalidCredentials()
    {
        $faker = \Faker\Factory::create();
        $randomUsername = $faker->userName;
        $randomPassword = $faker->password(/*$minLength =*/ 8);
        
        // sign in with random user credentials
        $this->dispatch("/sign/in", Request::METHOD_POST, array("username" => $randomUsername, "password" => $randomPassword));

        // Assert response is not a redirect
        $this->assertNotRedirect();
        
        $content = $this->getResponse()->getContent();
        // Assert proper error message is displayed
        $this->assertContains("Username and password are invalid !", $content);
    }

}
