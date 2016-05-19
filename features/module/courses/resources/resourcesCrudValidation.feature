Feature: course Acl

@javascript 
Scenario: Testing resources pages as Admin
    Given I mock the login session as "admin"
    And I go to "/resources/new/1"
    And I press "Add More"
    And I press "Create"
    Then I should see "Value is required and can't be empty" 4 times
    Then I should see "File was not uploaded" 2 times

Scenario: Testing resources extension as Admin
    Given I mock the login session as "admin"
    And I go to "/resources/new/1"
    And I attach the file "user.png" to "file"
    And I press "Create"
    Then I should see "File has an incorrect extension"
