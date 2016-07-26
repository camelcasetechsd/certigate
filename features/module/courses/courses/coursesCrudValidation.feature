Feature: course Acl

@javascript 
Scenario: Testing course pages as Admin
    Given I mock the login session as "admin"
    And I go to "/courses/new"
    And I press "Save"
    Then I should see "Value is required and can't be empty" 22 times

    
@javascript 
Scenario: Testing course pages as Admin with 5 more button
    Given I mock the login session as "admin"
    And I go to "/courses/new"
    And I press "Add Five More"
    And I press "Save"
    Then I should see "Value is required and can't be empty" 37 times


