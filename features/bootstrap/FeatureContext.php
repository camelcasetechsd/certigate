<?php

use Behat\MinkExtension\Context\MinkContext;
use Behat\Behat\Event\FeatureEvent;
use Behat\Mink\Exception\ResponseTextException;
use Behat\Behat\Event\SuiteEvent;
use Behat\Behat\Event\ScenarioEvent;

if (!ini_get('date.timezone')) {
    date_default_timezone_set("UTC");
}
require_once dirname(__FILE__) . '/../application/ControllerTestCase.php';
require_once dirname(__FILE__) . '/../../module/Utilities/src/Utilities/Service/Process.php';

use Utilities\Service\Process;

class FeatureContext extends MinkContext
{

    /**
     * bootstrap table class 
     */
    const DEFAULT_TABLE_CLASS_TEXT = "table";

    /**
     * bootstrap class for deactivation
     */
    const DEACTIVATION_CLASS_TEXT = "container-inactive";

    protected $controllerInstance;

    /**
     * Initializes context.
     * Every scenario gets it's own context object.
     * 
     * @param array $parameters context parameters (set up via behat.yml)
     */
    public function __construct(array $parameters)
    {

        $this->controllerInstance = new ControllerTestCase();
        $this->controllerInstance->setUp();
        $this->useContext('organization_subcontext', new OrganizationSubContext(array(
            'controllerInstance' => $this->controllerInstance,
            'featureContext' => $this,
        )));
    }

    /**
     * @BeforeSuite
     * @param SuiteEvent $event
     */
    public static function prepare(SuiteEvent $event)
    {
        // start selenium server and phantomjs browser
        Process::runBackgroundProcess(/* $cmd = */ "vendor/bin/selenium-server-standalone");
        Process::runBackgroundProcess(/* $cmd = */ "phantomjs --webdriver=8643");
    }

    /**
     * @AfterSuite 
     * @param SuiteEvent $event
     */
    public static function teardown(SuiteEvent $event)
    {
        // kill selenium browser
        // phantomjs browser will be killed after behat fully closes, as it is needed in behat till the end
        Process::killProcessByName(/* $processName = */ "java");
    }

    /**
     * @BeforeFeature 
     * @param FeatureEvent $event
     */
    public static function setupFeature(FeatureEvent $event)
    {
        exec("bin/doctrine orm:schema-tool:drop --force");
        exec("bin/doctrine orm:schema-tool:update --force");
        exec("php public/estore/updateDB.php -e " . APPLICATION_ENV);
        exec("APPLICATION_ENV=" . APPLICATION_ENV . " ./vendor/bin/phinx seed:run -e " . APPLICATION_ENV);
    }

    /**
     * @BeforeScenario @javascript
     * @param ScenarioEvent $event
     */
    public function before(ScenarioEvent $event)
    {
        // set browser view port size to large one, so that all page components are visible
        $this->getSession()->resizeWindow(1440, 900, 'current');
    }

    /**
     * @AfterScenario @javascript
     * @param ScenarioEvent $event
     */
    public function after(ScenarioEvent $event)
    {
        // delete session, to avoid logged in user data in next scenarios, if in current scenario a user is logged in
        $session = $this->getSession()->getDriver()->getWebDriverSession();
        $session->deleteAllCookies();
    }

    /**
     * @When /^I load the URL "([^"]*)"$/
     */
    public function iLoadTheURL($url)
    {
        $this->controllerInstance->dispatch($url);
    }

    /**
     * @Then /^the module should be "([^"]*)"$/
     */
    public function theModuleShouldBe($desiredModule)
    {
        $this->controllerInstance->assertModule($desiredModule);
    }

    /**
     * @Given /^the controller should be "([^"]*)"$/
     */
    public function theControllerShouldBe($desiredController)
    {
        $this->controllerInstance->assertController($desiredController);
    }

    /**
     * @Given /^the action should be "([^"]*)"$/
     */
    public function theActionShouldBe($desiredAction)
    {
        $this->controllerInstance->assertAction($desiredAction);
    }

    /**
     * @Given /^the page should contain a "([^"]*)" tag that contains "([^"]*)"$/
     */
    public function thePageShouldContainATagThatContains($tag, $content)
    {
        $this->controllerInstance->assertQueryContentContains($tag, $content);
    }

    /**
     * @Given /^the action should not redirect$/
     */
    public function theActionShouldNotRedirect()
    {
        $this->controllerInstance->assertNotRedirect();
    }

    /**
     * @Given /^the action should redirect to "([^"]*)"$/
     */
    public function theActionShouldRedirectTo($newUrl)
    {
        $this->controllerInstance->assertRedirectTo($newUrl);
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
            case 'tmuser2':
                $username = "tmuser2";
                $password = "tmuser2";
                break;
            case 'tcauser':
                $username = "tcauser";
                $password = "tcauser";
                break;
            case 'tcauser2':
                $username = "tcauser2";
                $password = "tcauser2";
                break;
            case 'instructor':
                $username = "instructor";
                $password = "instructor";
                break;
            case 'student':
                $username = "student";
                $password = "student";
                break;
            case 'distributor':
                $username = "distributor";
                $password = "distributor";
                break;
            case 'distributor2':
                $username = "distributor2";
                $password = "distributor2";
                break;
            case 'reseller':
                $username = "reseller";
                $password = "reseller";
                break;
            case 'reseller2':
                $username = "reseller2";
                $password = "reseller2";
                break;
            default :
                $message = sprintf('User %s not registered as a test users', $role);
                throw new \Exception($message);
        }
        $this->visit('/sign/in');
        $this->fillField('username', $username);
        $this->fillField('password', $password);
        $this->pressButton('Sign in');
        $this->assertHomepage();
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
     * @Given /^I Check the "([^"]*)" labeled checkbox$/
     */
    public function iCheckTheLabeledCheckbox($id)
    {

        $element = $this->getSession()->getPage()->find('css', "#$id");
        if ($element) {
            $element->check();
            return;
        }
        else {
            throw new \Exception('checkbox not found');
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
     * @param string $field field identifer
     * @param string $value field value
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
     * @Then /^hidden field "([^"]*)" should be filled with "([^"]*)"$/
     * @param string $field field identifer
     * @param string $value field value
     */
    public function hiddenFieldShouldBeFilledWith($field, $value)
    {
        $hiddenField = $this->getSession()->getPage()->find('css', 'input[name="' . $field . '"]');
        if (is_null($hiddenField)) {
            throw new Exception("There's no hidden field with this name");
        }
        else {
            if ($value != $hiddenField->getValue()) {
                $message = sprintf('hidden field %s does not contain "%s" as expected it contains "%s" ', $field, $value, $hiddenField->getValue());
                throw new ResponseTextException($message, $this->getSession());
            }
        }
    }

    /**
     * @Then /^field "([^"]*)" should be filled with "([^"]*)"$/
     * @param string $field field identifer
     * @param string $value field value
     */
    public function fieldShouldBeFilledWith($field, $value)
    {
        $element = $this->getSession()->getPage()->find('css', 'input[name="' . $field . '"]');
        if (is_null($element)) {
            throw new Exception("There's no field with this name");
        }
        else {
            if ($value != $element->getValue()) {
                $message = sprintf('field %s does not contain "%s" as expected it contains "%s" ', $field, $value, $element->getValue());
                throw new ResponseTextException($message, $this->getSession());
            }
        }
    }

    /**
     * Check if field exists
     *
     * @Given /^(?:|I )should see field "(?P<field>(?:[^"]|\\")*)"$/
     * @param string $field field identifer
     */
    public function iSeeField($field)
    {
        $this->checkFieldExistance($field, /* $unexpectedExistanceStatus = */ false);
    }

    /**
     * Check if field does not exist
     *
     * @Given /^(?:|I )should not see field "(?P<field>(?:[^"]|\\")*)"$/
     * @param string $field field identifer
     */
    public function iCannotSeeField($field)
    {
        $this->checkFieldExistance($field, /* $unexpectedExistanceStatus = */ true);
    }

    /**
     * Check if field does exist or not
     *
     * @param string $field field identifer
     * @param bool $unexpectedExistanceStatus
     */
    public function checkFieldExistance($field, $unexpectedExistanceStatus)
    {
        $exists = $this->getSession()->getPage()->hasField($this->fixStepArgument($field));
        if ($exists === $unexpectedExistanceStatus) {
            $messageExistsOrNotString = ($unexpectedExistanceStatus === false) ? 'no' : 'a';
            $message = sprintf('There\'s %s field %s.', $messageExistsOrNotString, $field);
            throw new ResponseTextException($message, $this->getSession());
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
     * Checks, perform action on specific row in table
     * @When /^I perform "([^"]*)" action on row with "([^"]*)" value$/
     */
    public function iPerformActionOnRowWithValue($operation, $rowValue)
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
                    $message = sprintf('%s Action is not supported ...', ucfirst($operation));
                    throw new \Exception($message);
                }
                else {
                    $link->click();
                }
            }
        }
    }

    /**
     * Checks that specific row in table does not has specific action
     * @Then /^row with value "([^"]*)" should not contain "([^"]*)" action$/
     */
    public function rowWithValueShouldNotContainAction($rowValue, $operation)
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
                if (!is_null($link)) {
                    $message = sprintf('%s Action is supported ... , expected not to be supported for this row', ucfirst($operation));
                    throw new \Exception($message);
                }
                return;
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
        }
        else {
            // is active
            return;
        }
    }

    /**
     * Checks, that page contains specified text x times.
     *
     * @Then /^(?:|I )should see "(?P<text>(?:[^"]|\\")*)" (?P<times>\d+) times?$/
     */
    public function assertPageContainsTextWithNumber($text, $times)
    {
        $this->pageTextContainsWithNumber($this->fixStepArgument($text), $times);
    }

    /**
     * Checks, that page contains text matching specified pattern x times.
     *
     * @Then /^(?:|I )should see text matching (?P<pattern>"(?:[^"]|\\")*") (?P<times>\d+) times?$/
     */
    public function assertPageMatchesTextWithNumber($pattern, $times)
    {
        $this->pageTextMatchesWithNumber($this->fixStepArgument($pattern), $times);
    }

    /**
     * Checks that current page contains text for x times.
     *
     * @param string $text
     * @param int $times
     *
     * @throws ResponseTextException
     */
    public function pageTextContainsWithNumber($text, $times)
    {
        $regex = '/' . preg_quote($text, '/') . '/ui';
        $this->pageTextMatchesWithNumber($regex, $times);
    }

    /**
     * Checks that current page text matches regex for x times.
     *
     * @param string $regex
     * @param int $times
     * 
     * @throws ResponseTextException
     */
    public function pageTextMatchesWithNumber($regex, $times)
    {
        $actual = preg_replace('/\s+/u', ' ', $this->getSession()->getPage()->getText());
        $actualCount = preg_match_all($regex, $actual);
        if ($actualCount != intval($times)) {
            $message = sprintf('The pattern %s was found "%d" not "%d" in the text of the current page.', $regex, $actualCount, $times);
            throw new ResponseTextException($message, $this->getSession());
        }
    }

    /**
     * @Then /^I should find field with name "([^"]*)"$/
     */
    public function shouldFindFieldWithName($text)
    {
        $element = $this->getSession()->getPage()->find('xpath', "//*[@name='" . $text . "']");
        if (null === $element) {
            // option not found
            $message = sprintf('element with %s name not found ', $text);
            throw new \Exception($message);
        }
        return;
    }

    /**
     * @Then /^I should not find field with name "([^"]*)"$/
     */
    public function shouldNotFindFieldWithName($text)
    {
        $element = $this->getSession()->getPage()->find('xpath', "//*[@name='" . $text . "']");
        if (null != $element) {
            $message = sprintf('element with %s name is exist ', $text);
            throw new \Exception($message);
        }
        return;
    }

    /**
     * function to check if specific value is one of the values of a dropdown
     * @Then /^dropdown "([^"]*)" should contain "([^"]*)"$/
     */
    public function DropdownShouldContain($name, $value)
    {
        $element = $this->getSession()->getPage()->find('xpath', "//*[@name='" . $name . "']");
        if (null === $element) {
            $message = sprintf('element with name %s does not exist ', $name);
            throw new \Exception($message);
        }
        else {
            $option = $element->find('xpath', "//*[text()='" . $value . "']");
            if (null === $option) {
                $message = sprintf('option with value %s does not exist in drop down %s', $value, $name);
                throw new \Exception($message);
            }
            return;
        }
    }

    /**
     * function to check if specific value is one of the values of a dropdown
     * @Then /^dropdown "([^"]*)" should not contain "([^"]*)"$/
     */
    public function DropdownShouldNotContain($name, $value)
    {
        $element = $this->getSession()->getPage()->find('xpath', "//*[@name='" . $name . "']");
        if (null === $element) {
            $message = sprintf('element with name %s does not exist ', $name);
            throw new \Exception($message);
        }
        else {
            $option = $element->find('xpath', "//*[@text()='" . $value . "']");
            if (null != $option) {
                $message = sprintf('option with value %s is already exists in drop down %s', $value, $name);
                throw new \Exception($message);
            }
            return;
        }
    }

    /**
     * function for pressing inputs type button
     * @Given /^I press on input "([^"]*)"$/
     */
    public function iPressOnInput($id)
    {
        $page = $this->getSession()->getPage();
        $element = $page->find('xpath', "//input[@id='" . $id . "']");
        $element->click();
    }

    /**
     * @When /^I select "(?P<date>.+)" time from "(?P<id>.+)"$/
     */
    public function startDate($date, $id)
    {
        $this->getSession()->getPage()->find('css', '#' . $id)->setValue($date);
    }

}
