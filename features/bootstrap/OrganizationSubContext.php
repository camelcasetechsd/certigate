<?php

use Behat\Behat\Context\ClosuredContextInterface,
    Behat\Behat\Context\TranslatedContextInterface,
    Behat\Behat\Context\BehatContext,
    Behat\Behat\Exception\PendingException;
use Behat\Gherkin\Node\PyStringNode,
    Behat\Gherkin\Node\TableNode;

class OrganizationSubContext extends BehatContext
{

    protected $controllerInstance;
    protected $minkContext;
    protected $featureContext;
    protected $mainContext;

    public function __construct(array $parameters)
    {
        $this->controllerInstance = $parameters['controllerInstance'];
//        $this->minkContext = $parameters['minkContext'];
//        $this->featureContext = $parameters['featureContext'];
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

}
