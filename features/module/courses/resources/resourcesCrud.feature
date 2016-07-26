Feature: course Acl
 
Scenario: Creating resource type Presentation as Admin
    Given I mock the login session as "admin"
    And I go to "/courses/resources/new/1"
    And I select "Presentation" from "type"
    And I fill in "name" with "English Materials"
    And I fill in "nameAr" with "English Materials in Arabic"
    And I attach the file "text.pdf" to "resource_form_file"
    And I check "status"
    And I press on input with "name" "buttons[save]"


 
Scenario: Creating resource type Activities as Admin
    Given I mock the login session as "admin"
    And I go to "/courses/resources/new/1"
    And I select "Activities" from "type"
    And I fill in "name" with "Math Materials"
    And I fill in "nameAr" with "Math Materials in Arabic"
    And I attach the file "text.pdf" to "resource_form_file"
    And I check "status"
    And I press on input with "name" "buttons[save]"


 
Scenario: Creating resource type Exams as Admin
    Given I mock the login session as "admin"
    And I go to "/courses/resources/new/1"
    # another resource
    And I select "Exams" from "type"
    And I fill in "name" with "French Exam"
    And I fill in "nameAr" with "French Exam in Arabic"
    And I attach the file "text.pdf" to "resource_form_file"
    And I check "status"
    And I press on input with "name" "buttons[save]"


 
Scenario: Creating resource type Course Updates as Admin
    Given I mock the login session as "admin"
    And I go to "/courses/resources/new/1"
    And I select "Course Updates" from "type"
    And I fill in "name" with "Deutsch Materials"
    And I fill in "nameAr" with "Math Materials in Arabic"
    And I attach the file "text.pdf" to "resource_form_file"
    And I check "status"
    And I press on input with "name" "buttons[save]"


 
Scenario: Creating resource type Standards as Admin
    Given I mock the login session as "admin"
    And I go to "/courses/resources/new/1"
    And I select "Standards" from "type"
    And I fill in "name" with "PHP Standards"
    And I fill in "nameAr" with "PHP Standards in Arabic"
    And I attach the file "text.pdf" to "resource_form_file"
    And I check "status"
    And I press on input with "name" "buttons[save]"


 
Scenario: Creating resource type Ice Breakers as Admin
    Given I mock the login session as "admin"
    And I go to "/courses/resources/new/1"
    And I select "Ice Breakers" from "type"
    And I fill in "name" with "Science Fun"
    And I fill in "nameAr" with "Science Fun in Arabic"
    And I attach the file "text.pdf" to "resource_form_file"
    And I check "status"
    And I press on input with "name" "buttons[save]"



##//////////////////////// Testing Bulk Upload ////////////////////##
##  Having a problem with web Driver  throwing this error
##  [WebDriver\Exception\CurlExec]                                                                                         
##  Exception has been thrown in "afterScenario" hook, defined in FeatureContext::after()                                  
##  Curl error thrown for http DELETE to http://localhost:8643/wd/hub/session/5c659e40-1dd2-11e6-8b75-d16141151c28/cookie  
##  Failed to connect to localhost port 8643: Connection refused                   


## Note: it works fine if we stopped using selenium for this scenario
## but we will not be able to make bulk upload due to "Add More" button
## which uses javascript
@javascript 
Scenario: Creating resources pages as Admin
    Given I mock the login session as "admin"
    And I go to "/courses/resources/new/1"
    
    #first resource
    And I select "Presentation" from "type"
    And I fill in "name" with "English Materials"
    And I fill in "nameAr" with "English Materials in Arabic"
    And I attach the file "text.pdf" to "resource_form_file"
    And I press "Add More"
    
    # another resource
    And I fill in "nameAdded[0]" with "Math Materials"
    And I fill in "nameArAddedAr[0]" with "Math Materials in Arabic"
    And I attach the file "text.pdf" to "fileAdded[0]"
    And I press "Add More"

    And I press on input with "name" "buttons[save]"

