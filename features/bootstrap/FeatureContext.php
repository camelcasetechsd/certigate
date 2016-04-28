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

    const DEFAULT_TABLE_CLASS_TEXT = "table";
    const DEACTIVATION_CLASS_TEXT = "container-inactive";

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
    public function iMockTheLoginSession($extraString = false, $username = false, $password = false)
    {

        if ($username === false && $password === false) {
            $username = "admin";
            $password = "adminadmin";
        }
        $this->iAmOnHomepage();
        $this->fillField('username', $username);
        $this->fillField('password', $password);
        $this->pressButton('Sign in');
    }

    /**
     * @Given /^I mock the login session as "([^"]*)"?$/
     * 
     * @param string $extraString extra credentials provided : ' with "username" "password"' ,default is bool false
     * @param string $username optional username to login with ,default is bool false
     * @param string $password optional password to login with ,default is bool false
     */
    public function iMockTheLoginSessionAs($role)
    {
        switch ($role) {
            case 'admin':
                $username = "admin";
                $password = "adminadmin";
                break;
            case 'user':
                $username = "user";
                $password = "useruser";
                break;
            case 'tmuser':
                $username = "tmuser";
                $password = "tmuser";
                break;
            case 'tcauser':
                $username = "tcauser";
                $password = "tcauser";
                break;
            case 'instructor':
                $username = "instructor";
                $password = "instructor";
                break;
            case 'student':
                $username = "student";
                $password = "student";
                break;
        }
        $this->visit('/sign/in');
        $this->fillField('username', $username);
        $this->fillField('password', $password);
        $this->pressButton('Sign in');
    }

    /**
     * Presses button with specified xpath.
     *
     * @When /^(?:|I )press button with id "([^"]*)" and value "([^"]*)"?$/
     */
    public function pressButtonWithXpath($formId, $value)
    {
        $page = $this->getSession()->getPage();
        $element = $page->find('css', "#$formId input[value=\"$value\"]");
        $element->click();
    }

    /**
     * @When /^I Check the "([^"]*)" radio button set to "([^"]*)"?$/
     */
    public function iCheckTheRadioButtonSetTo($id, $value)
    {

        if ($value == "true" || $value == "false") {
            $value == "true" ? $value = 1 : $value = 0;
            $element = $this->getSession()->getPage()->find('css', "#$id");
            if ($element) {
                $this->getMainContext()->fillField($element);
                return;
            }
            else {
                throw new \Exception('Radio button not found');
            }
        }
        else {
            throw new \Exception('wrong option to set');
        }
    }

    /**
     * @When /^I check the "([^"]*)" radio button id with value of (?P<num>\d+)$/
     */
    public function iCheckTheRadioButtonIdWithValueOf($radioId, $value)
    {

        $labels = $this->getSession()->getPage()->findAll('css', 'label');
        foreach ($labels as $label) {
            if ($label->has('css', "input[value=$value]")) {
                $radioButton = $label->findById($radioId);
                $this->getSession()->getPage()->fillField($radioId, $radioButton->getAttribute('value'));
            }
        }
    }

    /**
     * @When /^I select the optgroup with (?P<num>\d+) option value from "([^"]*)"$/
     */
    public function iSelectTheOptgroupWithOptionValueFrom($optionId, $select)
    {
        $this->getSession()->getPage()->selectFieldOption($select, "$optionId");
    }

    /**
     * @Given /^I fill hidden field "([^"]*)" with "([^"]*)"$/
     */
    public function iFillHiddenFieldWith($field, $value)
    {
        $hiddenField = $this->getSession()->getPage()->find('css', 'input[name="' . $field . '"]');
        if (is_null($hiddenField)) {
            throw new Exception("There's no hidden field with this name");
        }
        else {
            $hiddenField->setValue($value);
        }
    }

    /**
     * Checks, number of rows is equal to specified.
     * @Then /^I should see only (\d+) row$/
     */
    public function iShouldSeeOnlyRow($rowsCount)
    {
        $table = $this->getSession()->getPage()->find('css', 'table[class="' . self::DEFAULT_TABLE_CLASS_TEXT . '"]');
        $tbody = $table->find('css', 'tbody');
        $rows = $tbody->findAll('css', 'tr');
        if (count($rows) == (int) $rowsCount) {
            return;
        }
        else {
            $errorMessage = sprintf('Number of specified rows does not match what appear on this page , expected %d then got %d', $rowsCount, count($rows));
            throw new \Exception($errorMessage);
        }
    }

    /**
     * Checks, preform action on specific row in table
     * @When /^I preform "([^"]*)" action on row with "([^"]*)" value$/
     */
    public function iPreformActionOnRowWithValue($operation, $rowValue)
    {
        $table = $this->getSession()->getPage()->find('css', 'table[class="' . self::DEFAULT_TABLE_CLASS_TEXT . '"]');
        if (is_null($table)) {
            throw new \Exception('No Tables have been found');
        }
        else {
            $tbody = $table->find('css', 'tbody');
            $rows = $tbody->findAll('css', 'tr');
            $rawFlag = false;
            $myRow = null;
            foreach ($rows as $row) {
                if ($row->has('xpath', "//td[text()='" . $rowValue . "']")) {
                    $myRow = $row;
                    if (!is_null($myRow)) {
                        $rawFlag = true;
                        break;
                    }
                }
            }
            if (!$rawFlag) {
                $message = sprintf('There\'s no value in rows like %s', $rowValue);
                throw new \Exception($message);
            }
            else {
                $link = $myRow->findLink(ucfirst($operation));
                if (is_null($link)) {
                    $message = sprintf('%s Action does not supported ...', ucfirst($operation));
                    throw new \Exception($message);
                }
                else {
                    $link->click();
                }
            }
        }
    }

    /**
     * @Then /^I should see "([^"]*)" selected from "([^"]*)"$/
     */
    public function iShouldSeeOptionSelectedFrom($option, $select)
    {
        $selectedOption = $this->getSession()->getPage()->find('xpath', "//select[@name='" . $select . "']/option[text()='" . $option . "']");
        if (null === $selectedOption) {
            // option not found
            $message = sprintf('%s not found in %s select', $option, $select);
            throw new \Exception($message);
        }
        else if ($selectedOption->hasAttribute('selected')) {
            // if selected
            return;
        }
        else {
            // if not selected
            $message = sprintf('%s is not selected in %s select field', $option, $select);
            throw new \Exception($message);
        }
    }

    /**
     * @Then /^I should see row with "([^"]*)" text deactivated$/
     */
    public function iShouldSeeRowWithTextDeactivated($text)
    {
        $row = $this->getSession()->getPage()->find('xpath', "//tr[@class='" . self::DEACTIVATION_CLASS_TEXT . "']/td[text()='" . $text . "']");
        if (null === $row) {
            // option not found
            $message = sprintf('Deactivated row with %s text not found ', $text);
            throw new \Exception($message);
        }
    }

    /**
     * @Then /^I should see row with "([^"]*)" text activated$/
     */
    public function iShouldSeeRowWithTextActivated($text)
    {
        $row = $this->getSession()->getPage()->find('xpath', "//tr/td[text()='" . $text . "']");
        if (null === $row) {
            // option not found
            $message = sprintf('Row with %s text not found ', $text);
            throw new \Exception($message);
        }
        else if ($row->hasClass(self::DEACTIVATION_CLASS_TEXT)) {
            // option not found
            $message = sprintf('Row with %s text is Deactivated ', $text);
            throw new \Exception($message);
        }else{
            // is active
            return;
        }
    }

}
