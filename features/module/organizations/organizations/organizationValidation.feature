Feature: Organization Validation
    Testing admin, training manager and testing center administrator available operations

Scenario: admin select organization type without required fields
    Given I mock the login session as "admin"
    And I am on "/organizations/type"
    Then I press "Start!"
    And I should see "Value is required and can't be empty"

@javascript
Scenario: admin create organization without required fields
    Given I mock the login session
        And I am on "/organizations/new/1/2/3/4"
        And I uncheck "status"
        And I attach the file "user.png" to "CRAttachment"
        Then I press "Create"
        And I should be on "/organizations/new/1/2/3/4"
        And I should see "Value is required and can't be empty" 43 times
        And I should see "Latitude is required"
        And I should see "Longitude is required"
