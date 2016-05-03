Feature: Release Page Crud
### release page Crud Tests

Scenario: Test Press Release page as Admin && Test Subscription

   Given I mock the login session as "admin"
   And I go to "/cms/press-release"
   ### checking if the page is fine
   Then the response should not contain "<h1>An error occurred</h1>"
   Then the response should not contain "<b>Warning</b>"
   Then the response should not contain "<b>Notice</b>"
   ### checking if the data is fine
   Then I should see "Press Releases"
   Then I should see "EDITED PRESS TEST"
   Then I should see "By Certigate Tester"
   Then I should see "This is the new press release summary"
   Then I should see "Read more ..."
   When I press "Subscribe"
   Then I should see "You have been subscribed successfully."
   When I press "Unsubscribe"
   Then I should see "You have been unsubscribed successfully."
   When I follow "Read more ..."
   Then I should be on "/press/19"
 