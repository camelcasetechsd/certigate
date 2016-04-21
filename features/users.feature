Feature: Users

Scenario: List create user
    Given I mock the login session
    And I am on "/"
    And I follow "users"
    Then I should be on "/users"

Scenario: open user form 
    Given I mock the login session
    And I am on "/users"
    And I follow "Create new User"
    Then I should be on "/users/new"

Scenario: create user
    Given I mock the login session
    And I am on "/users/new"
    When I fill in "username" with "TestUser"
    When I fill in "password" with "password"
    When I fill in "confirmPassword" with "password"
    When I fill in "name" with "User"
    When I fill in "mobile" with "01115991948"
    When I fill in "dateOfBirth" with "02/15/1992"
    When I fill in "startDate" with "07/01/2015"
    When I fill in "vacationBalance" with "20"
    When I fill in "description" with "New Employee"
    When I select "Single" from "maritalStatus"
    When I select "Cairo Branch" from "branch"
    When I select "CSI Department" from "department"
    When I select "Manager Manager" from "manager"
    When I select "Junior Software Developer" from "position"
    When I attach the file "user.png" to "photo"
    And I press "Create"
    Then I should be on "/users"
