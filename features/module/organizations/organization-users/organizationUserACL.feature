Feature: organizationuserACL

Scenario: Access organization users pages as Admin 
    Given I mock the login session as "admin"
    And I go to "/organization-users/1"
    Then I should see "ORGANIZATION USERS"

    #ATP Organization
    And I go to "/organization-users/new/1"
    Then I should see "NEW ORGANIZATION USER"
    Then dropdown "role" should contain "Training Manager"
    Then dropdown "role" should not contain "Proctor"
    Then dropdown "role" should not contain "Test Center Administrator"    

    #ATC Organization
    And I go to "/organization-users/new/2"
    Then I should see "NEW ORGANIZATION USER"
    Then dropdown "role" should contain "Proctor"
    Then dropdown "role" should contain "Test Center Administrator"    
    Then dropdown "role" should not contain "Training Manager"

    #ATP/ATC Organization
    And I go to "/organization-users/new/3"
    Then I should see "NEW ORGANIZATION USER"
    Then dropdown "role" should contain "Proctor"
    Then dropdown "role" should contain "Test Center Administrator"    
    Then dropdown "role" should contain "Training Manager"

    #Distributor Organization
    And I go to "/organization-users/new/4"
    Then I should  not see "NEW ORGANIZATION USER"
    Then I should  see "This type of organization not supposed to have any organization users"
    


