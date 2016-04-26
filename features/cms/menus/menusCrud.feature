Feature: Menus Crud
### Menu Crud Tests

Scenario: create menus as Admin

    Given I mock the login session as "admin"
    And I go to "/cms/menu/new"
    When I fill in the following:
      | title | testMenu |
      | titleAr | testMenuAr |
    When I check "status"   
    And I press "Create"
    Then I should be on "/cms/menu"
    Then I should see "testMenu"
    Then I should see "testMenuAr"
    Then the response should not contain "<b>Error</b>"
    Then the response should not contain "<b>Warning</b>"
    Then the response should not contain "<b>Notice</b>"


Scenario: edit menus as Admin

    Given I mock the login session as "admin"
    And I go to "/cms/menu/edit/3"
    Then the "status" checkbox should be checked
    #Then the "#menu_form_title" element should contain "testMenu"    
    #Then the "#menu_form_titleAr" element should contain "testMenuAr"    
    When I fill in the following:
      | title | NewTestMenu |
      | titleAr | NewTestMenuAr |
    When I check "status"   
    And I press "Edit"
    Then I should be on "/cms/menu"
    Then I should see "NewTestMenu"
    Then I should see "NewTestMenuAr"
    Then the response should not contain "<b>Error</b>"
    Then the response should not contain "<b>Warning</b>"
    Then the response should not contain "<b>Notice</b>"


Scenario: delete menus as Admin

    Given I mock the login session as "admin"
    And I go to "/cms/menu/delete/3"
    Then I should be on "/cms/menu"
    Then the response should contain "container-inactive"
    Then the response should not contain "<b>Error</b>"
    Then the response should not contain "<b>Warning</b>"
    Then the response should not contain "<b>Notice</b>"