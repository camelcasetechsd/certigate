Feature: course Acl

@javascript 
Scenario: Testing course pages as Admin
    Given I mock the login session as "admin"
    And I go to "/course-events/new"
    And I press "Create"
    Then I should see "Value is required and can't be empty" 9 times


@javascript 
Scenario: Testing full capacity
    Given I mock the login session as "admin"
    And I go to "/course-events/new"
    And I fill in "capacity" with "10"
    Then field "studentsNo" should be filled with ""
    And I press "Full Capacity"
    Then field "studentsNo" should be filled with "10"


@javascript 
Scenario: checking the diff between "/course-events/new" vs "/course-events/new/course_id"
    Given I mock the login session as "admin"
    And I go to "/course-events/new"
    Then I should see field "course"
    And I go to "/course-events/new/1"
    Then I should not see field "course"


@javascript 
Scenario: checking date difference validation
    Given I mock the login session as "admin"
    And I go to "/course-events/new"
    And I fill in "startDate" with "03/06/2016"
    And I fill in "endDate" with "01/06/2016"
    And I press "Create"
    Then I should see "End date should be after Start date"


