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
    And I fill in "course_form_time" with "13:00"
    And I fill in "duration" with "885"
    And I fill in "price" with "13"
    
    #adding 2 outlines
    And I fill in "outlines[0][title]" with "outline 1"
    And I fill in "outlines[0][titleAr]" with "outline 1 in Arabic"
    And I fill in "outlines[0][duration]" with "30"
    And I check "outlines[0][status]"
    And I fill in "outlines[1][title]" with "outline 2"
    And I fill in "outlines[1][titleAr]" with "outline 2 in Arabic"
    And I fill in "outlines[1][duration]" with "20"
    And I check "outlines[1][status]"
    
    #removing the other field sets 
    And I press "removeOutline2"
    And I press "removeOutline3"
    And I press "removeOutline4"
    
    #saving and publishing the course
    And I press "Save and Publish"

    #checking if everything is alright
    Then print last response
    Then I should be on "/courses"
    Then I should see "Hello 7"
    Then I should see "course name in Arabic"
    And I go to "/courses/more/2"
    Then I should see "Hello 7"
    Then I should see "885 day(s)"
    Then I should see "Hello 7"
    Then I should see "course name in Arabic"
    Then the response should contain "Course Outlines"
    Then the response should contain "outline 1"
    Then the response should contain "outline 1 in Arabic"
    Then the response should contain "outline 2 "
    Then the response should contain "outline 2 in Arabic"
    Then the response should contain "30"
    Then the response should contain "20"


    #checking edit
    And I go to "/courses"
    And I perform "Edit" action on row with "Hello 7" value
    Then print current URL
    Then I should see "EDIT COURSE"
    