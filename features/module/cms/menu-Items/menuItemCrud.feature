Feature: Menus Crud
### Menu Crud Tests

Scenario: create menuitem page as Admin
    
    ### first we create the page itself
    Given I mock the login session as "admin"
    And I go to "/cms/page/new"
    Then I should see "NEW PAGE"
    When I fill in the following:
      | title | testPage |
      | titleAr | testPageAr |
      | path | /testPage |
      | body | This is page body |
      | bodyAr | This is page body in Arabic |
    And I select "Page" from "type"  
    And I press "Save and Publish"
    Then I should be on "/cms/page"
    
    ### now we crate menu item for the page itself
    And I go to "/cms/menuitem/new"
    When I check the "type-page" radio button id with value of 1
    
    ### 9 for "--Contact Us" parent menu item
    And I select "9" from "parent"
    
    ### 1 for primary menu
    And I fill hidden field "menu" with "1"
    And I select "Reports" from "page"
    When I fill in the following:
      | title | test Page MenuItem |
      | titleAr | test Page MenuItem Ar |
      | weight | 1000 |
    When I check "status"   
    And I press "Create"
    Then I should be on "/cms/menuitem"
    When I go to "/cms/menuitem?page=7"
    Then I should see "test Page MenuItem"
    Then the response should not contain "<h1>An error occurred</h1>"
    Then the response should not contain "<b>Warning</b>"
    Then the response should not contain "<b>Notice</b>"


Scenario: create menuitem direct url as Admin
    
    Given I mock the login session as "admin"
    And I go to "/cms/menuitem/new"
    When I check the "type-directUrl" radio button id with value of 2
    
    ### 9 for "--Contact Us" parent menu item
    And I select "9" from "parent"
    
    ### 1 for primary menu
    And I fill hidden field "menu" with "1"
    When I fill in the following:
      | title | test direct-url MenuItem |
      | titleAr | test direct-url MenuItem Ar |
      | directUrl | /test-directurl-menuitem |
      | weight | 1000 |
    When I check "status"   
    And I press "Create"
    Then I should be on "/cms/menuitem"
    When I go to "/cms/menuitem?page=7"
    Then I should see "test direct-url MenuItem"
    Then I should see "/test-directurl-menuitem"
    Then the response should not contain "<h1>An error occurred</h1>"
    Then the response should not contain "<b>Warning</b>"
    Then the response should not contain "<b>Notice</b>"


Scenario: filter menu items with title only

    Given I mock the login session as "admin"
    When I go to "/cms/menuitem"
    When I fill in the following:
      | title | test Page MenuItem |
    And I press "Filter"
    Then I should see only 1 row
    Then I should see "test Page MenuItem"
    Then I should see "1000"
    Then I should see "Primary Menu"
    Then the response should not contain "<h1>An error occurred</h1>"
    Then the response should not contain "<b>Warning</b>"
    Then the response should not contain "<b>Notice</b>"


Scenario: filter menu items with url only

    Given I mock the login session as "admin"
    When I go to "/cms/menuitem"
    When I fill in the following:
      | title | test direct-url MenuItem |
      | directUrl | /test-directurl-menuitem |
    And I press "Filter"
    Then I should see only 1 row
    Then I should see "test direct-url MenuItem"
    Then I should see "1000"
    Then I should see "Primary Menu"
    Then the response should not contain "<h1>An error occurred</h1>"
    Then the response should not contain "<b>Warning</b>"
    Then the response should not contain "<b>Notice</b>"


Scenario: Edit menu items as Admin

    Given I mock the login session as "admin"
    When I go to "/cms/menuitem"
    When I fill in the following:
      | title | test Page MenuItem |
    And I press "Filter"
    Then I should see only 1 row
    When I preform "edit" action on row with "test Page MenuItem" value
    Then I should see "EDIT MENU ITEM"

    ### 1 for "- About" parent menu item
    And I select "1" from "parent"

    When I fill in the following:
      | title | edited page |
      | titleAr | edited page Ar |
      | weight | 5000 |        
    And I press "Edit"
    Then I should be on "/cms/menuitem"
    Then the response should not contain "<h1>An error occurred</h1>"
    Then the response should not contain "<b>Warning</b>"
    Then the response should not contain "<b>Notice</b>"
    When I fill in the following:
      | title | test Page MenuItem |
    And I press "Filter"
    Then I should see only 0 row
    When I fill in the following:
      | title | edited page |
    And I press "Filter"
    Then I should see only 1 row
    Then I should see "edited page"
    Then I should see "5000"
    Then I should see "About"
    Then the response should not contain "<h1>An error occurred</h1>"
    Then the response should not contain "<b>Warning</b>"
    Then the response should not contain "<b>Notice</b>"


Scenario: delete menuitem as Admin

    Given I mock the login session as "admin"
    When I go to "/cms/menuitem"
    When I fill in the following:
      | title | edited page |
    And I press "Filter"
    Then I should see only 1 row
    When I preform "delete" action on row with "edited page" value
    When I fill in the following:
      | title | edited page |
    And I press "Filter"
    Then I should see only 1 row
    Then the response should contain "container-inactive"
    Then the response should not contain "<h1>An error occurred</h1>"
    Then the response should not contain "<b>Warning</b>"
    Then the response should not contain "<b>Notice</b>"


Scenario: reactivate menuitem as Admin

    Given I mock the login session as "admin"
    When I go to "/cms/menuitem"
    When I fill in the following:
      | title | edited page |
    And I press "Filter"
    Then I should see only 1 row
    When I preform "activate" action on row with "edited page" value
    When I fill in the following:
      | title | edited page |
    And I press "Filter"
    Then I should see only 1 row
    Then the response should not contain "container-inactive"
    Then the response should not contain "<h1>An error occurred</h1>"
    Then the response should not contain "<b>Warning</b>"
    Then the response should not contain "<b>Notice</b>"


