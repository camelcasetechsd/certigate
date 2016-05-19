Feature: course curd

@javascript 
Scenario: Testing course creation by Admin
    Given I mock the login session as "admin"
    And I go to "/courses/new"
    And I should see "NEW COURSE"
    And I fill in "name" with "Hello 7"
    And I fill in "nameAr" with "course name in Arabic"
    And I fill in "brief" with "course brief"
    And I fill in "briefAr" with "course brief in Arabic"
    And I fill in "course_form_time" with "17:39:57" 
    And I fill in "duration" with "885"
    And I fill in "price" with "13"
    
    #adding 2 outlines
    And I fill in "outlines[0][title]" with "outline 1"
    And I fill in "outlines[0][titleAr]" with "outline 1 in Arabic"
    And I fill in "outlines[0][duration]" with "30"
    And I check "outlines[0][status]"
    And I fill in "outlines[1][title]" with "outline 2"
    And I fill in "outlines[1][titleAr]" with "outline 2 in Arabic"
    And I fill in "outlines[1][duration]" with "7779"
    And I check "outlines[1][status]"
    
    #removing the other field sets 
    And I press "removeOutline2"
    And I press "removeOutline3"
    And I press "removeOutline4"
    
    #saving and publishing the course
    And I press "Save and Publish"

    #checking if everything is alright
    Then I should be on "/courses"
    Then I should see "Hello 7"
    Then I should see "course name in Arabic"
    And I go to "/courses/more/2"
    Then I should see "Hello 7"
    Then I should see "course name in Arabic"
    Then I should see "885 day(s)"
    Then the response should contain "Course Outlines"
    Then the response should contain "outline 1"
    Then the response should contain "outline 1 in Arabic"
    Then the response should contain "outline 2 "
    Then the response should contain "outline 2 in Arabic"
    Then the response should contain "30"
    Then the response should contain "7779"


    #checking edit
    And I go to "/courses"
    And I perform "Edit" action on row with "Hello 7" value
    Then I should see "EDIT COURSE"
    
    #testing time , name , brief , duration , outlines
    And I fill in "name" with "Hello XYZ"
    And I fill in "nameAr" with "changed course name in Arabic"
    And I fill in "brief" with "changed course brief"
    And I fill in "briefAr" with "changed course brief in Arabic"
    And I fill in "course_form_time" with "12:07:24" 
    And I fill in "duration" with "888"
    And I fill in "price" with "16.99"
    
    #adding 2 outlines
    And I fill in "outlines[0][title]" with "outline XF86"
    And I fill in "outlines[0][titleAr]" with "outline 1 in Arabic-EG"
    And I fill in "outlines[0][duration]" with "1234"
    And I check "outlines[0][status]"

    #checking  case of unchecked status for outline should not appear  
    And I uncheck "outlines[1][status]"
    And I press "Save and Publish"

    #checking if edit process done successfully 
    And I go to "/courses/more/2"
    Then I should see "Hello XYZ"
    Then I should see "changed course name in Arabic"
    Then I should see "888 day(s)"
    Then the response should contain "Course Outlines"
    Then the response should contain "outline XF86"
    Then the response should contain "outline 1 in Arabic-EG"
    Then the response should contain "1234"
    Then the response should not contain "outline 2 "
    Then the response should not contain "outline 2 in Arabic"
    Then the response should not contain "7779"    


@javascript
Scenario: creating course for trainers
    Given I mock the login session as "admin"
    And I go to "/courses/new"
    And I fill in "name" with "Pro Trainer"
    And I fill in "nameAr" with "Pro Trainer in Arabic"
    And I fill in "brief" with "Pro Trainer course brief"
    And I fill in "briefAr" with "Pro Trainer course brief in Arabic"
    And I fill in "course_form_time" with "13:02:57" 
    And I fill in "duration" with "729"
    And I fill in "price" with "183"
    And I check "CIP"
    
    #adding 2 outlines
    And I fill in "outlines[0][title]" with "Pro Trainer outline 1"
    And I fill in "outlines[0][titleAr]" with "Pro Trainer outline 1 in Arabic"
    And I fill in "outlines[0][duration]" with "456"
    And I check "outlines[0][status]"
    And I fill in "outlines[1][title]" with "Pro Trainer outline 2"
    And I fill in "outlines[1][titleAr]" with "Pro Trainer outline 2 in Arabic"
    And I fill in "outlines[1][duration]" with "111"
    And I check "outlines[1][status]"
    
    #removing the other field sets 
    And I press "removeOutline2"
    And I press "removeOutline3"
    And I press "removeOutline4"
    
    #saving and publishing the course
    And I press "Save and Publish"
    
    And I go to "/courses/calendar"
    Then I should not see "Pro Trainer"
    And I go to "/courses/instructor-training"
    Then I should see "Pro Trainer"


@javascript
Scenario: creating course for trainers
    Given I mock the login session as "admin"
    And I go to "/courses/new"
    And I fill in "name" with " Next Z Trainer Plus"
    And I fill in "nameAr" with "Next Trainer in Arabic"
    And I fill in "brief" with "Next Trainer course brief"
    And I fill in "briefAr" with "Next Trainer course brief in Arabic"
    And I fill in "course_form_time" with "13:02:57" 
    And I fill in "duration" with "729"
    And I fill in "price" with "183"
    And I check "CIP"
    
    #adding 2 outlines
    And I fill in "outlines[0][title]" with "Next Trainer outline 1"
    And I fill in "outlines[0][titleAr]" with "Next Trainer outline 1 in Arabic"
    And I fill in "outlines[0][duration]" with "456"
    And I check "outlines[0][status]"
    And I fill in "outlines[1][title]" with "Next Trainer outline 2"
    And I fill in "outlines[1][titleAr]" with "Next Trainer outline 2 in Arabic"
    And I fill in "outlines[1][duration]" with "111"
    And I check "outlines[1][status]"
    
    #removing the other field sets 
    And I press "removeOutline2"
    And I press "removeOutline3"
    And I press "removeOutline4"
    
    #saving and publishing the course
    And I press "Save and Publish"
    
    # checking not seen to others 
    And I go to "/courses/calendar"
    Then I should not see "Next Pro Z Trainer Plus"
    
    #checking only fisrt assigned CIP dominates
    And I go to "/courses/instructor-training"
    Then I should not see "Pro Trainer"
    Then I should see "Next Z Trainer Plus"


    