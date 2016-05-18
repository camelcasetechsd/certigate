Feature: course Acl

@javascript 
Scenario: Testing course pages as Admin
    Given I mock the login session as "admin"
    And I go to "/courses/new"
    And I press "Save"
    Then I should see "Value is required and can't be empty" 22 times

    
@javascript 
Scenario: Testing course pages as Admin with 1 more button
    Given I mock the login session as "admin"
    And I go to "/courses/new"
    And I press "Add One More"
    And I press "Save"
    Then I should see "Value is required and can't be empty" 25 times

@javascript 
Scenario: Testing course pages as Admin with 5 more button
    Given I mock the login session as "admin"
    And I go to "/courses/new"
    And I press "Add Five More"
    And I press "Save"
    Then I should see "Value is required and can't be empty" 37 times

@javascript 
Scenario: Testing course pages as Admin with only 1 outline
    Given I mock the login session as "admin"
    And I go to "/courses/new"
    And I press "removeOutline1"
    And I press "removeOutline2"
    And I press "removeOutline3"
    And I press "removeOutline4"
    And I press "Save"
    Then I should see "Value is required and can't be empty" 10 times

    
