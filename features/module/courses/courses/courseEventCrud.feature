Feature: course event curd

@javascript 
Scenario: testing course events creation by Admin
    Given I mock the login session as "admin"
    
    #first we create the course to assign course event to it
    And I go to "/courses/new"
    And I fill in "name" with "Hello XYZ"
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

@javascript    
Scenario:  creating the course event
    Given I mock the login session as "admin"
    And I go to "/courses"
    And I perform "Course Events" action on row with "Hello XYZ" value
    Then I should see only 0 row
    And I press "Create new Course Event"
    
    And I select "atpDummy" from "atp"
    When I select "5" from "course_event_form_ai"
    And I fill in "capacity" with "50"
    And I fill in "studentsNo" with "10"
    And I fill in "startDateHj" with "27/08/1437"
    And I fill in "startDate" with "03/06/2016"
    And I fill in "endDateHj" with "29/08/1437"
    And I fill in "endDate" with "05/06/2016"
    And I press "Create"
    Then I should be on "/course-events/2"
    Then I should see only 1 row

    
@javascript    
Scenario:  creating the course event by TM
    Given I mock the login session as "tmuser"
    And I go to "/course-events/new"
    And I select "Hello XYZ" from "course"
    And I select "atpDummy" from "atp"
    When I select "5" from "course_event_form_ai"
    And I fill in "capacity" with "50"
    And I fill in "studentsNo" with "10"
    And I fill in "startDateHj" with "29/08/1437"
    And I fill in "startDate" with "05/06/2016"
    And I fill in "endDateHj" with "5/09/1437"
    And I fill in "endDate" with "10/06/2016"
    And I press "Create"
    Then I should be on "/course-events/2"
    Then I should see only 2 row

    
@javascript    
Scenario:  creating the course event with TM who is not assigned to any ATP 
    Given I mock the login session as "tmuser2"
    And I go to "/course-events/new"
    Then dropdown "atp" should not contain "atpDummy"
    Then dropdown "atp" should not contain "bothDummy"

    
@javascript    
Scenario:  creating the course event on old date to check if it disappears from course more page
    Given I mock the login session as "admin"
    And I go to "/courses"
    And I perform "Course Events" action on row with "Hello XYZ" value
    Then I should see only 2 row
    And I press "Create new Course Event"
    
    And I select "atpDummy" from "atp"
    When I select "5" from "course_event_form_ai"
    And I fill in "capacity" with "50"
    And I fill in "studentsNo" with "10"
    And I fill in "startDateHj" with "05/06/1431"
    And I fill in "startDate" with "19/05/2010"
    And I fill in "endDateHj" with "16/06/1432"
    And I fill in "endDate" with "19/05/2011"
    And I press "Create"
    Then I should be on "/course-events/2"
    Then I should see only 3 row

    #checking that the course event exists
    Then I should see "Wed, 19 May 2010"
    Then I should see "Thu, 19 May 2011"

    #making sure it does not appear 
    Then I go to "/courses/calendar"
    Then I should not see "Wed, 19 May 2010"
    Then I should not see "Thu, 19 May 2011"