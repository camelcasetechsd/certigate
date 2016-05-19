Feature: Course Calendar
    Testing user operations on courses via calendar
    As user role
    User can view course details, enroll and leave course

1 can enroll for not enrolled course event
2 enrolling course event ,should eventually turn enroll to cancel in calendar
3 can cancel enrollment for already enrolled course event
 full course event would have no enroll option
 outline download
 periodic notifications
 calendar alert
 more details page display course events, outlines and resources

Scenario: Testing hide from calendar which should hide course event from calendar and more details pages
    Given I mock the login session as "admin"
    And I go to "/courses/calendar"
        Then I should see "COURSES CALENDAR"
        #one course event exists and should be visible in calendar, so dates should appear in calendar
        But I should see text matching "[A-Z]{1}[a-z]{2}, [0-9]{1,2} [A-Z]{1}[a-z]{2,} 2[0-9]{3}"
        #one course event exists and should be visible in calendar, so periodic notifications button should appear
        And I should find field with name "subscribe"
    Then I follow "Read more ..." 
        Then I should see "COURSE EVENTS"
        #one course event exists and should be visible in calendar, so dates should appear in calendar
        But I should see text matching "[A-Z]{1}[a-z]{2}, [0-9]{1,2} [A-Z]{1}[a-z]{2,} 2[0-9]{3}"
    And I go to "/course-events"
    Then I should see "COURSE EVENTS"
        And I perform "Edit" action on row with "Active" value
    Then I should see "EDIT COURSE EVENT"
        And I check "hideFromCalendar"
        Then I press "Edit"
    Then I should be on "/course-events" 
    Then I go to "/sign/out" 
    #check calendar accessibility by student
    And I mock the login session as "student"
    And I go to "/courses/calendar"
        Then I should see "COURSES CALENDAR"
        #one course event exists and should be hidden in calendar, so no dates should appear in calendar
        But I should not see text matching "[A-Z]{1}[a-z]{2}, [0-9]{1,2} [A-Z]{1}[a-z]{2,} 2[0-9]{3}"
        #one course event exists and should be hidden in calendar, so periodic notifications button should not appear
        And I should not find field with name "subscribe"
    Then I follow "Read more ..." 
        Then I should see "COURSE EVENTS"
        #one course event exists and should be hidden in calendar, so no dates should appear in calendar
        But I should not see text matching "[A-Z]{1}[a-z]{2}, [0-9]{1,2} [A-Z]{1}[a-z]{2,} 2[0-9]{3}"

Scenario: Testing CIP and hide from calendar in both course calendar and instructor training
    Given I mock the login session as "admin"
    And I go to "/courses"
    Then I should see "COURSES"
        And I perform "Edit" action on row with "Active" value
    Then I should see "EDIT COURSE"
        And I check "isForInstructor"
        Then I press "Save and Publish"
    Then the "isForInstructor" checkbox should be checked
    And I go to "/courses/instructor-training"
        Then I should see "COURSE DETAILS"
        #one course event exists and should be visible in instructor training, so dates should appear in instructor training
        But I should see text matching "[A-Z]{1}[a-z]{2}, [0-9]{1,2} [A-Z]{1}[a-z]{2,} 2[0-9]{3}"
        And I should see "Start Date"
        And I should see "End Date"
        And I should see "ATP Name"
        And I should see "ATP City"
        # brief in english and arabic, but still one course
        And I should see "Brief" 2 times
        # duration is displayed for both the course and the outlines, but still one course
        And I should see "Duration" 2 times
    And I go to "/courses/calendar"
        Then I should see "COURSES CALENDAR"
        #one course event exists and should not be visible in calendar, so dates should appear in calendar
        But I should not see text matching "[A-Z]{1}[a-z]{2}, [0-9]{1,2} [A-Z]{1}[a-z]{2,} 2[0-9]{3}"
        And I should not see "Start Date"
        And I should not see "End Date"
        And I should not see "ATP Name"
        And I should not see "ATP City"
        And I should not see "Brief"
        And I should not see "Duration"
    And I go to "/course-events"
    Then I should see "COURSE EVENTS"
        And I perform "Edit" action on row with "Active" value
    Then I should see "EDIT COURSE EVENT"
        And I check "hideFromCalendar"
        Then I press "Edit"
    Then I should be on "/course-events" 
    Then I go to "/sign/out" 
    #check calendar accessibility by instructor
    And I mock the login session as "instructor"
    And I go to "/courses/instructor-training"
        Then I should see "COURSE DETAILS"
        #one course event exists and should be hidden in instructor training, so no dates should appear in instructor training
        But I should not see text matching "[A-Z]{1}[a-z]{2}, [0-9]{1,2} [A-Z]{1}[a-z]{2,} 2[0-9]{3}"
    And I go to "/courses/calendar"
        Then I should see "COURSES CALENDAR"
        #one course event exists and should not be visible in calendar, so dates should appear in calendar
        But I should not see text matching "[A-Z]{1}[a-z]{2}, [0-9]{1,2} [A-Z]{1}[a-z]{2,} 2[0-9]{3}"
        And I should not see "Start Date"
        And I should not see "End Date"
        And I should not see "ATP Name"
        And I should not see "ATP City"
        And I should not see "Read more ..." 