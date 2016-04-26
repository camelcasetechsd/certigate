Feature: Pages Crud
### Page Crud Tests

#Scenario: edit pages as Admin

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
    Then the response should not contain "<b>Error</b>"
    Then the response should not contain "<b>Warning</b>"
    Then the response should not contain "<b>Notice</b>"
    # checking if the created page works fine (does not work for now)
    When I go to "/newtestPage"
    Then the response should contain "NewtestPage</h1>"
    Then the response should not contain "<b>Error</b>"
    Then the response should not contain "<b>Warning</b>"
    Then the response should not contain "<b>Notice</b>"


#Scenario: unpublish pages as Admin
    
    Given I mock the login session as "admin"
    And I go to "/cms/page/edit/18"
    Then I should see "Edit PAGE"
    And I press "Unpublish"
    Then I should be on "/cms/page"
    Then the response should contain "container-inactive"
    Then the response should not contain "<b>Error</b>"
    Then the response should not contain "<b>Warning</b>"
    Then the response should not contain "<b>Notice</b>"
    # checking if the created page works fine
    When I go to "/newtestPage"
    Then the response should contain "Resource Not Found !"
    Then the response should not contain "<b>Error</b>"
    Then the response should not contain "<b>Warning</b>"
    Then the response should not contain "<b>Notice</b>"

@javascript
Scenario: create Press page as Admin

    Given I mock the login session as "admin"
    And I go to "/cms/page/new"
    Then I should see "NEW PAGE"
    And I select "Press Release" from "type"  
    And I select "Default" from "category"
    When I attach the file "user.png" to "picture"
    When I fill in the following:
      | title | PressTest |
      | titleAr | PressTestAr |
      | path | /testPress |
      | author | Certigate Tester |
      | summary | This is press release summary |
      | summaryAr | This is press release summary in Arabic |
      | body | This is press release body |
      | bodyAr | This is press release body in Arabic |
    And I press "Save and Publish"
    Then I should be on "/cms/page"
    Then I should see "PressTest"
    #checking if the list page is fine
    Then the response should not contain "<b>Error</b>"
    Then the response should not contain "<b>Warning</b>"
    Then the response should not contain "<b>Notice</b>"
    # checking if the created press page works fine
    When I go to "/press/19"
    Then the response should contain "PressTest</h3>"
    Then the response should contain "<span>By: Certigate Tester</span>"
    Then the response should contain "This is press release summary"
    Then the response should contain "This is press release body"
    Then the response should not contain "<b>Error</b>"
    Then the response should not contain "<b>Warning</b>"
    Then the response should not contain "<b>Notice</b>"
    #checking if the arabic page is fine
    Then the response should contain "PressTestAr</h3>"
    Then the response should contain "<span>By: Certigate Tester</span>"
    Then the response should contain "This is press release summary in Arabic"
    Then the response should contain "This is press release body in Arabic"
    Then the response should not contain "<b>Error</b>"
    Then the response should not contain "<b>Warning</b>"
    Then the response should not contain "<b>Notice</b>"
