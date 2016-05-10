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
    Then I should not see "NEW ORGANIZATION USER"
    Then I should see "This type of organization not supposed to have any organization users"
        
    #Reseller Organization
    And I go to "/organization-users/new/5"
    Then I should not see "NEW ORGANIZATION USER"
    Then I should see "This type of organization not supposed to have any organization users"
    


Scenario: Access organization users pages as TM 
    Given I mock the login session as "tmuser"
    And I go to "/organization-users/1"
    Then I should see "ORGANIZATION USERS"
    
    #ATP Organization
    And I go to "/organization-users/new/1"
    Then I should see "NEW ORGANIZATION USER"
    #proving that that user is registered as TM of this organization
    Then I should not see "You are currently not assigned to this organization"


    #ATC Organization
    And I go to "/organization-users/new/2"
    Then I should not see "NEW ORGANIZATION USER"
    # we check over ACL role first the ownership 
    Then I should see "You need to be Test Center Administrator, And you need to accept the corresponding Agreement statement"
    Then I should not see "You are currently not assigned to this organization"


    #ATP/ATC Organization
    And I go to "/organization-users/new/3"
    Then I should not see "NEW ORGANIZATION USER"
    # we check over ACL role first the ownership 
    Then I should not see "You need to be Test Center Administrator, And you need to accept the corresponding Agreement statement"
    Then I should see "You are currently not assigned to this organization"

    #Distributor Organization
    And I go to "/organization-users/new/4"
    Then I should not see "NEW ORGANIZATION USER"
    Then I should see "This type of organization not supposed to have any organization users"
    
    #Reseller Organization
    And I go to "/organization-users/new/5"
    Then I should not see "NEW ORGANIZATION USER"
    Then I should see "This type of organization not supposed to have any organization users"
  
  


Scenario: Access organization users pages as TM with the other TM who is not assigned to ATP
    Given I mock the login session as "tmuser2"
    And I go to "/organization-users/1"
    Then I should not see "ORGANIZATION USERS"
    Then I should see "You are currently not assigned to this organization"

    #ATP Organization
    And I go to "/organization-users/new/1"
    Then I should not see "NEW ORGANIZATION USER"
    #proving that that user is registered as TM of this organization
    Then I should see "You are currently not assigned to this organization"


    #ATC Organization
    And I go to "/organization-users/new/2"
    Then I should not see "NEW ORGANIZATION USER"
    # we check over ACL role first the ownership 
    Then I should see "You need to be Test Center Administrator, And you need to accept the corresponding Agreement statement"
    Then I should not see "You are currently not assigned to this organization"


    #ATP/ATC Organization
    And I go to "/organization-users/new/3"
    Then I should not see "NEW ORGANIZATION USER"
    # we check over ACL role first the ownership 
    Then I should not see "You need to be Test Center Administrator, And you need to accept the corresponding Agreement statement"
    Then I should see "You are currently not assigned to this organization"

    #Distributor Organization
    And I go to "/organization-users/new/4"
    Then I should not see "NEW ORGANIZATION USER"
    Then I should see "This type of organization not supposed to have any organization users"
    
    #Reseller Organization
    And I go to "/organization-users/new/5"
    Then I should not see "NEW ORGANIZATION USER"
    Then I should see "This type of organization not supposed to have any organization users"
    
    


Scenario: Access organization users pages as TCA 
    Given I mock the login session as "tcauser"
    And I go to "/organization-users/1"
    Then I should not see "ORGANIZATION USERS"
    Then I should see "You need to be Training Manager, And you need to accept the corresponding Agreement statement"

    And I go to "/organization-users/2"
    Then I should see "ORGANIZATION USERS"
    
    #ATP Organization
    And I go to "/organization-users/new/1"
    Then I should not see "NEW ORGANIZATION USER"
    Then I should see "You need to be Training Manager, And you need to accept the corresponding Agreement statement"


    #ATC Organization
    And I go to "/organization-users/new/2"
    Then I should see "NEW ORGANIZATION USER"
    # we check over ACL role first the ownership 
    Then I should not see "You need to be Test Center Administrator, And you need to accept the corresponding Agreement statement"
    Then I should not see "You are currently not assigned to this organization"


    #ATP/ATC Organization
    And I go to "/organization-users/new/3"
    Then I should not see "NEW ORGANIZATION USER"
    # we check over ACL role first the ownership 
    Then I should see "You are currently not assigned to this organization"

    #Distributor Organization
    And I go to "/organization-users/new/4"
    Then I should not see "NEW ORGANIZATION USER"
    Then I should see "This type of organization not supposed to have any organization users"
    
    #Reseller Organization
    And I go to "/organization-users/new/5"
    Then I should not see "NEW ORGANIZATION USER"
    Then I should see "This type of organization not supposed to have any organization users"
  
  

Scenario: Access organization users pages as TCA with the other TCA who is not assigned to ATC 
    Given I mock the login session as "tcauser2"
    And I go to "/organization-users/1"
    Then I should not see "ORGANIZATION USERS"
    Then I should see "You need to be Training Manager, And you need to accept the corresponding Agreement statement"

    And I go to "/organization-users/2"
    Then I should not see "ORGANIZATION USERS"
    Then I should see "You are currently not assigned to this organization"

    #ATP Organization
    And I go to "/organization-users/new/1"
    Then I should not see "NEW ORGANIZATION USER"
    Then I should see "You need to be Training Manager, And you need to accept the corresponding Agreement statement"


    #ATC Organization
    And I go to "/organization-users/new/2"
    Then I should see "NEW ORGANIZATION USER"
    # we check over ACL role first the ownership 
    Then I should not see "You need to be Test Center Administrator, And you need to accept the corresponding Agreement statement"
    Then I should see "You are currently not assigned to this organization"


    #ATP/ATC Organization
    And I go to "/organization-users/new/3"
    Then I should not see "NEW ORGANIZATION USER"
    # we check over ACL role first the ownership 
    Then I should see "You are currently not assigned to this organization"

    #Distributor Organization
    And I go to "/organization-users/new/4"
    Then I should not see "NEW ORGANIZATION USER"
    Then I should see "This type of organization not supposed to have any organization users"
    
    #Reseller Organization
    And I go to "/organization-users/new/5"
    Then I should not see "NEW ORGANIZATION USER"
    Then I should see "This type of organization not supposed to have any organization users"
  
  

Scenario: Access organization users pages as normal user 
    Given I mock the login session as "user"
    And I go to "/organization-users/1"
    Then I should see "You need to be Training Manager, And you need to accept the corresponding Agreement statement"

    And I go to "/organization-users/new/1"
    Then I should see "You need to be Test Center Administrator, And you need to accept the corresponding Agreement statement"

    And I go to "/organization-users/new/2"
    Then I should see "You need to be Training Manager, And you need to accept the corresponding Agreement statement"

    And I go to "/organization-users/new/4"
    Then I should see "This type of organization not supposed to have any organization users"

    And I go to "/organization-users/new/5"
    Then I should see "This type of organization not supposed to have any organization users"


Scenario: Access organization users pages as student 
    Given I mock the login session as "student"
    And I go to "/organization-users/1"
    Then I should see "You need to be Training Manager, And you need to accept the corresponding Agreement statement"

    And I go to "/organization-users/new/1"
    Then I should see "You need to be Test Center Administrator, And you need to accept the corresponding Agreement statement"

    And I go to "/organization-users/new/2"
    Then I should see "You need to be Training Manager, And you need to accept the corresponding Agreement statement"

    And I go to "/organization-users/new/4"
    Then I should see "This type of organization not supposed to have any organization users"

    And I go to "/organization-users/new/5"
    Then I should see "This type of organization not supposed to have any organization users"


Scenario: Access organization users pages as instructor 
    Given I mock the login session as "instructor"
    And I go to "/organization-users/1"
    Then I should see "You need to be Training Manager, And you need to accept the corresponding Agreement statement"

    And I go to "/organization-users/new/1"
    Then I should see "You need to be Test Center Administrator, And you need to accept the corresponding Agreement statement"

    And I go to "/organization-users/new/2"
    Then I should see "You need to be Training Manager, And you need to accept the corresponding Agreement statement"

    And I go to "/organization-users/new/4"
    Then I should see "This type of organization not supposed to have any organization users"

    And I go to "/organization-users/new/5"
    Then I should see "This type of organization not supposed to have any organization users"


Scenario: Access organization users pages as distributor 
    Given I mock the login session as "distributor"
    And I go to "/organization-users/1"
    Then I should see "You need to be Training Manager, And you need to accept the corresponding Agreement statement"

    And I go to "/organization-users/new/1"
    Then I should see "You need to be Test Center Administrator, And you need to accept the corresponding Agreement statement"

    And I go to "/organization-users/new/2"
    Then I should see "You need to be Training Manager, And you need to accept the corresponding Agreement statement"

    And I go to "/organization-users/new/4"
    Then I should see "This type of organization not supposed to have any organization users"

    And I go to "/organization-users/new/5"
    Then I should see "This type of organization not supposed to have any organization users"

Scenario: Access organization users pages as reseller 
    Given I mock the login session as "reseller"
    And I go to "/organization-users/1"
    Then I should see "You need to be Training Manager, And you need to accept the corresponding Agreement statement"

    And I go to "/organization-users/new/1"
    Then I should see "You need to be Test Center Administrator, And you need to accept the corresponding Agreement statement"

    And I go to "/organization-users/new/2"
    Then I should see "You need to be Training Manager, And you need to accept the corresponding Agreement statement"

    And I go to "/organization-users/new/4"
    Then I should see "This type of organization not supposed to have any organization users"

    And I go to "/organization-users/new/5"
    Then I should see "This type of organization not supposed to have any organization users"

