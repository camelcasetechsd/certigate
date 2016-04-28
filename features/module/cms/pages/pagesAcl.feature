Feature: PagesAcl
### ACL Tests

Scenario: List && create && edit  pages as Admin

    Given I mock the login session as "admin"
    And I go to "/cms/page"
    Then I should see "Pages"
    Then I should see "Create new Page"
    And I go to "/cms/page/new"
    Then I should see "NEW PAGE"
    And I go to "/cms/page/edit/1"
    Then I should see "EDIT PAGE"

Scenario: List && create && edit  pages as user

    Given I mock the login session as "user"
    And I go to "/cms/page"
    Then I should see "You don't have access to this , please contact the admin !"
    And I go to "/cms/page/new"
    Then I should see "You don't have access to this , please contact the admin !"
    And I go to "/cms/page/edit/1"
    Then I should see "You don't have access to this , please contact the admin !"
    
Scenario: List && create && edit  pages as tmuser

    Given I mock the login session as "tmuser"
    And I go to "/cms/page"
    Then I should see "You don't have access to this , please contact the admin !"
    And I go to "/cms/page/new"
    Then I should see "You don't have access to this , please contact the admin !"
    And I go to "/cms/page/edit/1"
    Then I should see "You don't have access to this , please contact the admin !"

Scenario: List && create && edit  pages as tcauser

    Given I mock the login session as "tcauser"
    And I go to "/cms/page"
    Then I should see "You don't have access to this , please contact the admin !"
    And I go to "/cms/page/new"
    Then I should see "You don't have access to this , please contact the admin !"
    And I go to "/cms/page/edit/1"
    Then I should see "You don't have access to this , please contact the admin !"

Scenario: List && create && edit  pages as instructor

    Given I mock the login session as "instructor"
    And I go to "/cms/page"
    Then I should see "You don't have access to this , please contact the admin !"
    And I go to "/cms/page/new"
    Then I should see "You don't have access to this , please contact the admin !"
    And I go to "/cms/page/edit/1"
    Then I should see "You don't have access to this , please contact the admin !"

Scenario: List && create && edit  pages as student

    Given I mock the login session as "student"
    And I go to "/cms/page"
    Then I should see "You don't have access to this , please contact the admin !"
    And I go to "/cms/page/new"
    Then I should see "You don't have access to this , please contact the admin !"
    And I go to "/cms/page/edit/1"
    Then I should see "You don't have access to this , please contact the admin !"
