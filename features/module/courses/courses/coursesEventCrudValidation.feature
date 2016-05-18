Feature: course Acl

@javascript 
Scenario: Testing course pages as Admin
    Given I mock the login session as "admin"
    And I go to "/course-events/new"
    And I press "Create"
    Then I should see "Value is required and can't be empty" 9 times