Feature: Release Page Crud
### release page Crud Tests

Scenario: create Press page as Admin

   Given I mock the login session as "admin"
   And I go to "/cms/page/new"
   Then I should see "NEW PAGE"
   And I select "Press Release" from "type"  
   And I select "Default" from "category"
   When I attach the file "git.png" to "picture"
   When I fill in the following:
     | title | PressTest |
     | titleAr | PressTestAr |
     ### should be removed 
     | path | /testPress |
     | author | Certigate Tester |
     | summary | This is press release summary |
     | summaryAr | This is press release summary in Arabic |
     | body | This is press release body |
     | bodyAr | This is press release body in Arabic |
   When I press button with id "page_form" and value "Save and Publish"
   Then I should be on "/cms/page"


Scenario: Making sure That the press release is created  

   Given I mock the login session as "admin"
   When I go to "/cms/page"
   Then I should see "PressTest"
   #checking if the list page is fine
   Then the response should not contain "<h1>An error occurred</h1>"
   Then the response should not contain "<b>Warning</b>"
   Then the response should not contain "<b>Notice</b>"
   # checking if the created press page works fine
   When I go to "/press/19"
   Then the response should contain "PressTest</h3>"
   Then the response should contain "<span>By: Certigate Tester</span>"
   Then the response should contain "This is press release summary"
   Then the response should contain "This is press release body"
   Then the response should not contain "<h1>An error occurred</h1>"
   Then the response should not contain "<b>Warning</b>"
   Then the response should not contain "<b>Notice</b>"
   #checking if the arabic page is fine
   When I go to "/trans/setlocale/ar_AR" 
   Then the response should contain "PressTestAr</h3>"
   Then the response should contain "<span>By: Certigate Tester</span>"
   Then the response should contain "This is press release summary in Arabic"
   Then the response should contain "This is press release body in Arabic"
   Then the response should not contain "<h1>An error occurred</h1>"
   Then the response should not contain "<b>Warning</b>"
   Then the response should not contain "<b>Notice</b>"


Scenario: Edit Press page as Admin

   Given I mock the login session as "admin"
   And I go to "/cms/page"
   When I preform "edit" action on row with "PressTest" value
   Then I should see "EDIT PAGE"
   Then I should see "Press Release" selected from "type"  
   Then I should see "Default" selected from "category"  
   When I attach the file "user.png" to "picture"
   When I fill in the following:
     | title | edited press test |
     | titleAr | edited press test Ar |
     ### should be removed
     | path | /newtestPress |
     | author | Certigate Tester |
     | summary | This is the new press release summary |
     | summaryAr | This is the new press release summary in Arabic |
     | body | This is the new press release body |
     | bodyAr | This is the new press release body in Arabic |
   When I press button with id "page_form" and value "Save and Publish"
   Then I should be on "/cms/page"


Scenario: Making sure That the press release is edited  

   Given I mock the login session as "admin"
   When I go to "/cms/page"
   Then I should see "edited press test"
   #checking if the list page is fine
   Then the response should not contain "<h1>An error occurred</h1>"
   Then the response should not contain "<b>Warning</b>"
   Then the response should not contain "<b>Notice</b>"
   # checking if the created press page works fine
   When I go to "/press/19"
   Then the response should contain "edited press test</h3>"
   Then the response should contain "<span>By: Certigate Tester</span>"
   Then the response should contain "This is the new press release summary"
   Then the response should contain "This is the new press release body"
   Then the response should not contain "<h1>An error occurred</h1>"
   Then the response should not contain "<b>Warning</b>"
   Then the response should not contain "<b>Notice</b>"
   #checking if the Arabic page is fine
   When I go to "/trans/setlocale/ar_AR" 
   Then the response should contain "edited press test Ar</h3>"
   Then the response should contain "<span>By: Certigate Tester</span>"
   Then the response should contain "This is the new press release summary in Arabic"
   Then the response should contain "This is the new press release body in Arabic"
   Then the response should not contain "<h1>An error occurred</h1>"
   Then the response should not contain "<b>Warning</b>"
   Then the response should not contain "<b>Notice</b>"


Scenario: deleting (deactivating) Release Page as Admin   

   Given I mock the login session as "admin"
   When I go to "/cms/page"
   Then I should see "edited press test"
   When I preform "delete" action on row with "edited press test" value
   Then I should be on "/cms/page"
   Then I should see row with "edited press test" text deactivated
   #checking if the list page is fine
   Then the response should not contain "<h1>An error occurred</h1>"
   Then the response should not contain "<b>Warning</b>"
   Then the response should not contain "<b>Notice</b>"
   # checking if the created press page works fine
   When I go to "/press/19"
   Then the response should contain "Resource Not Found !"
   Then the response should not contain "edited press test</h3>"
   Then the response should not contain "<span>By: Certigate Tester</span>"
   Then the response should not contain "This is the new press release summary"
   Then the response should not contain "This is the new press release body"
   Then the response should not contain "<h1>An error occurred</h1>"
   Then the response should not contain "<b>Warning</b>"
   Then the response should not contain "<b>Notice</b>"


Scenario: activating Release Page as Admin   

   Given I mock the login session as "admin"
   When I go to "/cms/page"
   Then I should see "edited press test"
   When I preform "activate" action on row with "edited press test" value
   Then I should be on "/cms/page"
   Then I should see row with "edited press test" text activated
   #checking if the list page is fine
   Then the response should not contain "<h1>An error occurred</h1>"
   Then the response should not contain "<b>Warning</b>"
   Then the response should not contain "<b>Notice</b>"
   # checking if the created press page works fine
   When I go to "/press/19"
   Then the response should contain "edited press test</h3>"
   Then the response should contain "<span>By: Certigate Tester</span>"
   Then the response should contain "This is the new press release summary"
   Then the response should contain "This is the new press release body"
   Then the response should not contain "<h1>An error occurred</h1>"
   Then the response should not contain "<b>Warning</b>"
   Then the response should not contain "<b>Notice</b>"
   #checking if the Arabic page is fine
   When I go to "/trans/setlocale/ar_AR" 
   Then the response should contain "edited press test Ar</h3>"
   Then the response should contain "<span>By: Certigate Tester</span>"
   Then the response should contain "This is the new press release summary in Arabic"
   Then the response should contain "This is the new press release body in Arabic"
   Then the response should not contain "<h1>An error occurred</h1>"
   Then the response should not contain "<b>Warning</b>"
   Then the response should not contain "<b>Notice</b>"

