Feature: Homepage
  In order to moke the login session
  I need to see the home page

    Scenario: Check the homepage
      Given I mock the login session
      And I am on "/"
      Then I should be on "/"