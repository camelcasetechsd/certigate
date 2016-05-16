<?php

use Behat\Behat\Context\ClosuredContextInterface,
    Behat\Behat\Context\TranslatedContextInterface,
    Behat\Behat\Context\BehatContext,
    Behat\Behat\Exception\PendingException;
use Behat\Gherkin\Node\PyStringNode,
    Behat\Gherkin\Node\TableNode;

class OrganizationSubContext extends BehatContext
{

    const RENEWAL_CLASS_TEXT = "warning";

    protected $controllerInstance;
    protected $minkContext;
    protected $featureContext;
    protected $mainContext;

    public function __construct(array $parameters)
    {
        $this->controllerInstance = $parameters['controllerInstance'];
        $this->mainContext = $this->getMainContext();
    }

    /**
     * @Then /^(?:|I )should see atc fields$/
     */
    public function shouldSeeATCFields()
    {
        $atcFields = $this->controllerInstance->application->getServiceManager()->get('config')['atpSkippedParams'];
        foreach ($atcFields as $name) {
            $this->getMainContext()->shouldFindFieldWithName($name);
        }
    }

    /**
     * @Then /^(?:|I )should see atp fields$/
     */
    public function shouldSeeATPFields()
    {
        $atcFields = $this->controllerInstance->application->getServiceManager()->get('config')['atcSkippedParams'];
        foreach ($atcFields as $name) {
            $this->getMainContext()->shouldFindFieldWithName($name);
        }
    }

    /**
     * @Then /^(?:|I )should not see atc fields$/
     */
    public function shouldNotSeeATCFields()
    {
        $atcFields = $this->controllerInstance->application->getServiceManager()->get('config')['atpSkippedParams'];
        foreach ($atcFields as $name) {
            $this->getMainContext()->shouldNotFindFieldWithName($name);
        }
    }

    /**
     * @Then /^(?:|I )should not see atp fields$/
     */
    public function shouldNotSeeATPFields()
    {
        $atcFields = $this->controllerInstance->application->getServiceManager()->get('config')['atcSkippedParams'];
        foreach ($atcFields as $name) {
            $this->getMainContext()->shouldNotFindFieldWithName($name);
        }
    }

    /**
     * @Then /^(?:|I )should find organization with name "([^"]*)" type "([^"]*)" and expiration "([^"]*)"$/
     */
    public function shouldFindOrganizationWithNameTypeAndExpiration($name, $type, $date)
    {
        $tr = $this->getMainContext()->getSession()->getPage()->find('xpath', "//tr[td[text()='" . $name . "'] and td[text()='" . $type . "'] and td[text()='" . $date . "']]");
        if (null === $tr) {
            $message = sprintf('specified organization does not exist');
            throw new \Exception($message);
        }
    }

    /**
     * @Then /^(?:|I )should see atc renewal fields$/
     */
    public function shouldSeeATCRenewalFields()
    {
        $atcFields = $this->controllerInstance->application->getServiceManager()->get('config')['AtcRenewalFields'];
        foreach ($atcFields as $name) {
            $this->getMainContext()->shouldFindFieldWithName($name);
        }
    }

    /**
     * @Then /^(?:|I )should not see atc renewal fields$/
     */
    public function shouldNotSeeATCRenwalFields()
    {
        $atcFields = $this->controllerInstance->application->getServiceManager()->get('config')['AtcRenewalFields'];
        foreach ($atcFields as $name) {
            $this->getMainContext()->shouldNotFindFieldWithName($name);
        }
    }

    /**
     * @Then /^(?:|I )should see atp renewal fields$/
     */
    public function shouldSeeATPRenewalFields()
    {
        $atpFields = $this->controllerInstance->application->getServiceManager()->get('config')['AtpRenewalFields'];
        foreach ($atpFields as $name) {
            $this->getMainContext()->shouldFindFieldWithName($name);
        }
    }

    /**
     * @Then /^(?:|I )should not see atp renewal fields$/
     */
    public function shouldNotSeeATPRenwalFields()
    {
        $atpFields = $this->controllerInstance->application->getServiceManager()->get('config')['AtpRenewalFields'];
        foreach ($atpFields as $name) {
            $this->getMainContext()->shouldNotFindFieldWithName($name);
        }
    }

    /**
     * @Then /^I should see organization with "([^"]*)" commercial name need to be renewed$/
     */
    public function iShouldSeeOrganizationWithCommercialNameNeedToBeRenewed($commercialName)
    {
        $row = $this->getMainContext()->getSession()->getPage()->find('xpath', "//tr[@class='" . self::RENEWAL_CLASS_TEXT . "']/td[text()='" . $commercialName . "']");
        if (null === $row) {
            // option not found
            $message = sprintf('Organization %s not found ', $commercialName);
            throw new \Exception($message);
        }
        else {
            
        }
    }

}
