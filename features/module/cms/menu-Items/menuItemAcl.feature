Feature: MenuItemAcl
### ACL Tests

Scenario: List && create && edit  menu items as Admin

    Given I mock the login session as "admin"
    And I go to "/cms/menuitem"
    Then I should see "MENU ITEMS"
    Then I should see "Create new Menu Item"
    And I go to "/cms/menuitem/new"
    Then I should see "NEW MENU ITEM"
    And I go to "/cms/menuitem/edit/1"
    Then I should see "EDIT MENU ITEM"

Scenario: List && create && edit  menus items as user

    Given I mock the login session as "user"
    And I go to "/cms/menuitem"
    Then I should see "You don't have access to this , please contact the admin !"
    And I go to "/cms/menuitem/new"
    Then I should see "You don't have access to this , please contact the admin !"
    And I go to "/cms/menuitem/edit/1"
    Then I should see "You don't have access to this , please contact the admin !"
   
Scenario: List && create && edit  menus items as tmuser

    Given I mock the login session as "tmuser"
    And I go to "/cms/menuitem"
    Then I should see "You don't have access to this , please contact the admin !"
    And I go to "/cms/menuitem/new"
    Then I should see "You don't have access to this , please contact the admin !"
    And I go to "/cms/menuitem/edit/1"
    Then I should see "You don't have access to this , please contact the admin !"


Scenario: List && create && edit  menus items as tcauser

    Given I mock the login session as "tcauser"
    And I go to "/cms/menuitem"
    Then I should see "You don't have access to this , please contact the admin !"
    And I go to "/cms/menuitem/new"
    Then I should see "You don't have access to this , please contact the admin !"
    And I go to "/cms/menuitem/edit/1"
    Then I should see "You don't have access to this , please contact the admin !"

Scenario: List && create && edit  menus items as instructor

    Given I mock the login session as "instructor"
    And I go to "/cms/menuitem"
    Then I should see "You don't have access to this , please contact the admin !"
    And I go to "/cms/menuitem/new"
    Then I should see "You don't have access to this , please contact the admin !"
    And I go to "/cms/menuitem/edit/1"
    Then I should see "You don't have access to this , please contact the admin !"


Scenario: List && create && edit  menus items as student

    Given I mock the login session as "student"
    And I go to "/cms/menuitem"
    Then I should see "You don't have access to this , please contact the admin !"
    And I go to "/cms/menuitem/new"
    Then I should see "You don't have access to this , please contact the admin !"
    And I go to "/cms/menuitem/edit/1"
    Then I should see "You don't have access to this , please contact the admin !"
