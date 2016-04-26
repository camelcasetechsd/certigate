Feature: MenusAcl
### ACL Tests

Scenario: List && create && edit  menus as Admin

    Given I mock the login session as "admin"
    And I go to "/cms/menu"
    Then I should see "Menus"
    Then I should see "Create new Menu"
    And I go to "/cms/menu/new"
    Then I should see "NEW MENU"
    And I go to "/cms/menu/edit/1"
    Then I should see "EDIT MENU"

Scenario: List && create && edit  menus as user

    Given I mock the login session as "user"
    And I go to "/cms/menu"
    Then I should see "You don't have access to this , please contact the admin !"
    And I go to "/cms/menu/new"
    Then I should see "You don't have access to this , please contact the admin !"
    And I go to "/cms/menu/edit/1"
    Then I should see "You don't have access to this , please contact the admin !"
    
Scenario: List && create && edit  menus as tmuser

    Given I mock the login session as "tmuser"
    And I go to "/cms/menu"
    Then I should see "You don't have access to this , please contact the admin !"
    And I go to "/cms/menu/new"
    Then I should see "You don't have access to this , please contact the admin !"
    And I go to "/cms/menu/edit/1"
    Then I should see "You don't have access to this , please contact the admin !"

Scenario: List && create && edit  menus as tcauser

    Given I mock the login session as "tcauser"
    And I go to "/cms/menu"
    Then I should see "You don't have access to this , please contact the admin !"
    And I go to "/cms/menu/new"
    Then I should see "You don't have access to this , please contact the admin !"
    And I go to "/cms/menu/edit/1"
    Then I should see "You don't have access to this , please contact the admin !"

Scenario: List && create && edit  menus as instructor

    Given I mock the login session as "instructor"
    And I go to "/cms/menu"
    Then I should see "You don't have access to this , please contact the admin !"
    And I go to "/cms/menu/new"
    Then I should see "You don't have access to this , please contact the admin !"
    And I go to "/cms/menu/edit/1"
    Then I should see "You don't have access to this , please contact the admin !"

Scenario: List && create && edit  menus as student

    Given I mock the login session as "student"
    And I go to "/cms/menu"
    Then I should see "You don't have access to this , please contact the admin !"
    And I go to "/cms/menu/new"
    Then I should see "You don't have access to this , please contact the admin !"
    And I go to "/cms/menu/edit/1"
    Then I should see "You don't have access to this , please contact the admin !"
