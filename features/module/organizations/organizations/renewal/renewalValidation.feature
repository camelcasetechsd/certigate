Feature: renewal Validation

@javascript
Scenario: test form validation 
    Given I mock the login session as "tmuser"
    And I go to "/organizations/myorganizations"
    When I perform "Renewal" action on row with "Atp test 1" value
    Then I should see atp renewal fields
    And I fill in "atpLicenseNo" with ""
    And I fill in "atpLicenseExpirationHj" with ""
    And I fill in "atpLicenseExpiration" with ""
    Then I press "Renew"
    And I should see "wire transfer is required" 1 times


