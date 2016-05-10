Feature: organization Acl
### ACL Tests

Scenario: test selecting organization type as Admin
    Given I mock the login session as "admin"
    Then I should be on "/"
    And I go to "/organizations/type"
    And I Check the "type-1" labeled checkbox 
    And I press "Start!"
    Then I should be on "/organizations/new/1"
    Then I should see atc fields
    Then I should not see atp fields

    
    And I go to "/organizations/type"
    And I Check the "type-2" labeled checkbox 
    And I press "Start!"
    Then I should be on "/organizations/new/2"
    Then I should not see atc fields
    Then I should see atp fields

    
    And I go to "/organizations/type"
    And I Check the "type-3" labeled checkbox 
    And I press "Start!"
    Then I should be on "/organizations/new/3"
    Then I should not see atc fields
    Then I should not see atp fields

    
    And I go to "/organizations/type"
    And I Check the "type-4" labeled checkbox 
    And I press "Start!"
    Then I should be on "/organizations/new/4"
    Then I should not see atc fields
    Then I should not see atp fields


    And I go to "/organizations/type"
    And I Check the "type-1" labeled checkbox 
    And I Check the "type-2" labeled checkbox 
    And I Check the "type-3" labeled checkbox 
    And I Check the "type-4" labeled checkbox 
    And I press "Start!"
    And I should be on "/organizations/new/1/2/3/4"
    Then I should see atc fields
    Then I should see atp fields


###########################


Scenario: test selecting organization type as TM

    Given I mock the login session as "tmuser"
    Then I should be on "/"
    And I go to "/organizations/type"
    And I Check the "type-1" labeled checkbox 
    And I press "Start!"
    Then I should see "You need to be Test Center Administrator, And you need to accept the corresponding Agreement statement"


########

    And I go to "/organizations/type"
    And I Check the "type-2" labeled checkbox 
    And I press "Start!"
    Then I should be on "/organizations/new/2"
    Then I should see "Create new Organization"
    Then I should not see atc fields
    Then I should see atp fields

########

    And I go to "/organizations/type"
    And I Check the "type-3" labeled checkbox 
    And I press "Start!"
    Then I should see "You need to be Distributor, And you need to accept the corresponding Agreement statement"

########

    And I go to "/organizations/type"
    And I Check the "type-4" labeled checkbox 
    And I press "Start!"
    Then I should see "You need to be Re-Seller, And you need to accept the corresponding Agreement statement"


######################


Scenario: test selecting organization type as TCA

    Given I mock the login session as "tcauser"
    Then I should be on "/"
    And I go to "/organizations/type"
    And I Check the "type-1" labeled checkbox 
    And I press "Start!"
    Then I should be on "/organizations/new/1"
    Then I should see "Create new Organization"
    Then I should see atc fields
    Then I should not see atp fields
    

########

    And I go to "/organizations/type"
    And I Check the "type-2" labeled checkbox 
    And I press "Start!"
    Then I should see "You need to be Training Manager, And you need to accept the corresponding Agreement statement"

########

    And I go to "/organizations/type"
    And I Check the "type-3" labeled checkbox 
    And I press "Start!"
    Then I should see "You need to be Distributor, And you need to accept the corresponding Agreement statement"

########

    And I go to "/organizations/type"
    And I Check the "type-4" labeled checkbox 
    And I press "Start!"
    Then I should see "You need to be Re-Seller, And you need to accept the corresponding Agreement statement"

###########################


Scenario: test selecting organization type as user

    Given I mock the login session as "user"
    Then I should be on "/"
    And I go to "/organizations/type"
    And I Check the "type-1" labeled checkbox 
    And I press "Start!"
    Then I should see "You need to be Test Center Administrator, And you need to accept the corresponding Agreement statement"

########

    And I go to "/organizations/type"
    And I Check the "type-2" labeled checkbox 
    And I press "Start!"
    Then I should see "You need to be Training Manager, And you need to accept the corresponding Agreement statement"

#####

    And I go to "/organizations/type"
    And I Check the "type-3" labeled checkbox 
    And I press "Start!"
    Then I should see "You need to be Distributor, And you need to accept the corresponding Agreement statement"

#####

    And I go to "/organizations/type"
    And I Check the "type-4" labeled checkbox 
    And I press "Start!"
    Then I should see "You need to be Re-Seller, And you need to accept the corresponding Agreement statement"

###########################


