Feature: organization user

@javascript
Scenario: creating new TCA for atcDummy organization 

    Given I mock the login session as "tcauser"
    And I go to "/organizations/myorganizations"
    When I perform "Manage Users" action on row with "atcDummy" value
    Then I should see only 1 row
    And I press "Add new Organization User"
    Then I should see "NEW ORGANIZATION USER"
    And I select the optgroup with 6 option value from "user"
    And I select "Test Center Administrator" from "role"
    And I press "Create"
    Then I should see only 2 row
    
Scenario: checking if user has the role and agreed to role agreement (id = 6 -> student) 

    Given I mock the login session as "student"
    And I go to "/organizations/myorganizations"
    When I perform "Manage Users" action on row with "atcDummy" value
    Then I should see "You need to be Test Center Administrator, And you need to accept the corresponding Agreement statement(s)"
    And I follow "user profile"
    And I check "testCenterAdministratorStatement"
    And I press "Edit"
    And I go to "/organizations/myorganizations"
    When I perform "Manage Users" action on row with "atcDummy" value
    Then I should not see "You need to be Test Center Administrator, And you need to accept the corresponding Agreement statement(s)"
    
@javascript
Scenario: changing the role of a TCA to proctor 
 
    Given I mock the login session as "tcauser"
    And I go to "/organizations/myorganizations"
    When I perform "Manage Users" action on row with "atcDummy" value
    Then I should see only 2 row
    When I perform "Edit" action on row with "Test Center Administrator" value
    Then I should see "EDIT ORGANIZATION USER"
    Then I should see "Test Center Administrator" selected from "role"
    And I select "Proctor" from "role"
    And I press "Edit"
    Then I should see only 2 row
    And I go to "/users/edit/4"
    Then the "Test Center Administrator" checkbox should be checked
    Then the "Proctor" checkbox should be checked