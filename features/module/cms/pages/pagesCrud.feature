Feature: Pages Crud
### Page Crud Tests

 Scenario: create pages as Admin

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
    Then I should see "testPage"
    Then I should see "/testPage"
    #checking if the list page is fine
    Then the response should not contain "<h1>An error occurred</h1>"
    Then the response should not contain "<b>Warning</b>"
    Then the response should not contain "<b>Notice</b>"
    # checking if the created page works fine
    When I go to "/testPage"
    Then the response should contain "testPage</h1>"
    Then the response should not contain "<h1>An error occurred</h1>"
    Then the response should not contain "<b>Warning</b>"
    Then the response should not contain "<b>Notice</b>"


Scenario: edit pages as Admin

    Given I mock the login session as "admin"
    And I go to "/cms/page/edit/18"
    Then I should see "Edit PAGE"
    When I fill in the following:
      | title | NewtestPage |
      | titleAr | NewtestPageAr |
      | path | /newtestPage |
      | body | This is the new page body |
      | bodyAr | This is the new page body in Arabic |
    And I select "Page" from "type"  
    And I press "Save and Publish"
    Then I should be on "/cms/page"
    Then I should see "NewtestPage"
    Then I should see "/newtestPage"
    #checking if the list page is fine
    Then the response should not contain "<h1>An error occurred</h1>"
    Then the response should not contain "<b>Warning</b>"
    Then the response should not contain "<b>Notice</b>"
    # checking if the created page works fine (does not work for now)
    When I go to "/newtestPage"
    Then the response should contain "NewtestPage</h1>"
    Then the response should not contain "<h1>An error occurred</h1>"
    Then the response should not contain "<b>Warning</b>"
    Then the response should not contain "<b>Notice</b>"


Scenario: unpublish pages as Admin
    
    Given I mock the login session as "admin"
    And I go to "/cms/page/edit/18"
    Then I should see "Edit PAGE"
    And I press "Unpublish"
    Then I should be on "/cms/page"
    Then the response should contain "container-inactive"
    Then the response should not contain "<h1>An error occurred</h1>"
    Then the response should not contain "<b>Warning</b>"
    Then the response should not contain "<b>Notice</b>"
    # checking if the created page works fine
    When I go to "/newtestPage"
    Then the response should contain "Resource Not Found !"
    Then the response should not contain "<h1>An error occurred</h1>"
    Then the response should not contain "<b>Warning</b>"
    Then the response should not contain "<b>Notice</b>"

