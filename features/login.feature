Feature: test the login functionality

    Scenario: Failed Login
    Given I am on "/sign/in"
    When I fill in the following:
      | username | nouser@nomail.com |
      | password | nopassword |
    And I press "login"
    Then I should be on "/sign/in"

  Scenario: Successful Login
    Given I am on "/sign/in"
    When I fill in the following:
      | username | admin |
      | password | adminadmin |
    And I press "login"
    Then I should be on "/"

