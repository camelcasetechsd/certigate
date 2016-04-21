<?php

use Behat\Behat\Context\ClosuredContextInterface,
    Behat\Behat\Context\TranslatedContextInterface,
    Behat\Behat\Context\BehatContext,
    Behat\Behat\Exception\PendingException;
use Behat\Gherkin\Node\PyStringNode,
    Behat\Gherkin\Node\TableNode;
use Behat\MinkExtension\Context\MinkContext;
if (!ini_get('date.timezone')) {
    date_default_timezone_set("UTC");
}
require_once dirname(__FILE__) . '/../application/ControllerTestCase.php';

class FeatureContext extends MinkContext
{

    protected $app;

    /**
     * Initializes context.
     * Every scenario gets it's own context object.
     * 
     * @param array $parameters context parameters (set up via behat.yml)
     */
    public function __construct(array $parameters)
    {
        $this->app = new ControllerTestCase();
        $this->app->setUp();
    }

    /**
     * @When /^I load the URL "([^"]*)"$/
     */
    public function iLoadTheURL($url)
    {
        $this->app->dispatch($url);
    }

    /**
     * @Then /^the module should be "([^"]*)"$/
     */
    public function theModuleShouldBe($desiredModule)
    {
        $this->app->assertModule($desiredModule);
    }

    /**
     * @Given /^the controller should be "([^"]*)"$/
     */
    public function theControllerShouldBe($desiredController)
    {
        $this->app->assertController($desiredController);
    }

    /**
     * @Given /^the action should be "([^"]*)"$/
     */
    public function theActionShouldBe($desiredAction)
    {
        $this->app->assertAction($desiredAction);
    }

    /**
     * @Given /^the page should contain a "([^"]*)" tag that contains "([^"]*)"$/
     */
    public function thePageShouldContainATagThatContains($tag, $content)
    {
        $this->app->assertQueryContentContains($tag, $content);
    }

    /**
     * @Given /^the action should not redirect$/
     */
    public function theActionShouldNotRedirect()
    {
        $this->app->assertNotRedirect();
    }

    /**
     * @Given /^the action should redirect to "([^"]*)"$/
     */
    public function theActionShouldRedirectTo($newUrl)
    {
        $this->app->assertRedirectTo($newUrl);
    }

    /**
     * @Given /^I mock the login session( with "([^"]*)" "([^"]*)")?$/
     * 
     * @param string $extraString extra credentials provided : ' with "username" "password"' ,default is bool false
     * @param string $username optional username to login with ,default is bool false
     * @param string $password optional password to login with ,default is bool false
     */
    public function iMockTheLoginSession($extraString = false, $username = false, $password = false) {

        if($username === false && $password === false){
            $username = "admin";
            $password = "adminadmin";
        }
        $this->iAmOnHomepage();
        $this->fillField('username', $username);
        $this->fillField('password', $password);
        $this->pressButton('Sign in');
    }

}
