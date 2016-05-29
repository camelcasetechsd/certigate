Feature: organization Acl
### ACL Tests

Scenario: List && create && edit  organization as Admin

    Given I mock the login session as "admin"
    Then I should be on "/"
    And I go to "/organizations"
    Then I should see "ORGANIZATIONS"
    Then I should see "Create new Organization"
    And I go to "/organizations/myorganizations"
    Then I should see "MY ORGANIZATIONS"
    And I go to "/organizations/type"
    Then I should see "Organization Type"
    Then I should see "ATC Organization"
    Then I should see "ATP Organization"
    Then I should see "Distributor Organization"
    Then I should see "Re-Seller Organization"
    And I go to "/organizations/atcs"
    Then I should see "ATCS"
    And I go to "/organizations/atps"
    Then I should see "ATPS"
    And I go to "/organizations/distributors"
    Then I should see "DISTRIBUTORS"
    And I go to "/organizations/resellers"
    Then I should see "RE-SELLERS"


    #all the following organizations with id 1,2,3,4 are ATC , ATP , Distributor , Re-seller 
    And I go to "/organizations/new/1"
    Then I should see "CREATE AN ORGANIZATION"
    And I go to "/organizations/new/2"
    Then I should see "CREATE AN ORGANIZATION"
    And I go to "/organizations/new/3"
    Then I should see "CREATE AN ORGANIZATION"
    And I go to "/organizations/new/4"
    Then I should see "CREATE AN ORGANIZATION"



Scenario: List && create && edit  organization as TM

    Given I mock the login session as "tmuser"
    Then I should be on "/"
    And I go to "/organizations"
    Then I should see "You don't have access to this page , please contact the admin !"
    And I go to "/organizations/myorganizations"
    Then I should see "ORGANIZATIONS"
    And I go to "/organizations/type"
    Then I should see "Organization Type"
    And I go to "/organizations/atcs"
    Then I should see "ATCS"
    And I go to "/organizations/atps"
    Then I should see "ATPS"
    And I go to "/organizations/distributors"
    Then I should see "DISTRIBUTORS"
    And I go to "/organizations/resellers"
    Then I should see "RE-SELLERS"

    #all the following organizations with id 1,2,3,4 are ATC , ATP , Distributor , Re-seller 
    And I go to "/organizations/new/1"
    Then I should see "You need to be Test Center Administrator, And you need to accept the corresponding Agreement statement"
    And I go to "/organizations/new/2"
    Then I should see "CREATE AN ORGANIZATION"
    And I go to "/organizations/new/3"
    Then I should see "You need to be Distributor, And you need to accept the corresponding Agreement statement"
    And I go to "/organizations/new/4"
    Then I should see "You need to be Re-Seller, And you need to accept the corresponding Agreement statement"

Scenario: List && create && edit  organization as TCA

    Given I mock the login session as "tcauser"
    And I go to "/organizations"
    Then I should see "You don't have access to this page , please contact the admin !"
    And I go to "/organizations/myorganizations"
    Then I should see "ORGANIZATIONS"
    And I go to "/organizations/type"
    Then I should see "Organization Type"
    And I go to "/organizations/atcs"
    Then I should see "ATCS"
    And I go to "/organizations/atps"
    Then I should see "ATPS"
    And I go to "/organizations/distributors"
    Then I should see "DISTRIBUTORS"
    And I go to "/organizations/resellers"
    Then I should see "RE-SELLERS"

    #all the following organizations with id 1,2,3,4 are ATC , ATP , Distributor , Re-seller 
    And I go to "/organizations/new/1"
    Then I should see "CREATE AN ORGANIZATION"
    And I go to "/organizations/new/2"
    Then I should see "You need to be Training Manager, And you need to accept the corresponding Agreement statement"
    And I go to "/organizations/new/3"
    Then I should see "You need to be Distributor, And you need to accept the corresponding Agreement statement"
    And I go to "/organizations/new/4"
    Then I should see "You need to be Re-Seller, And you need to accept the corresponding Agreement statement"


Scenario: List && create && edit  organization as Distributor

    Given I mock the login session as "distributor"
    Then I should be on "/"
    And I go to "/organizations"
    Then I should see "You don't have access to this page , please contact the admin !"
    And I go to "/organizations/myorganizations"
    Then I should see "ORGANIZATIONS"
    And I go to "/organizations/type"
    Then I should see "Organization Type"
    And I go to "/organizations/atcs"
    Then I should see "ATCS"
    And I go to "/organizations/atps"
    Then I should see "ATPS"
    And I go to "/organizations/distributors"
    Then I should see "DISTRIBUTORS"
    And I go to "/organizations/resellers"
    Then I should see "RE-SELLERS"

    #all the following organizations with id 1,2,3,4 are ATC , ATP , Distributor , Re-seller 
    And I go to "/organizations/new/1"
    Then I should see "You need to be Test Center Administrator, And you need to accept the corresponding Agreement statement"
    And I go to "/organizations/new/2"
    Then I should see "You need to be Training Manager, And you need to accept the corresponding Agreement statement"
    And I go to "/organizations/new/3"
    Then I should see "CREATE AN ORGANIZATION"
    And I go to "/organizations/new/4"
    Then I should see "You need to be Re-Seller, And you need to accept the corresponding Agreement statement"



Scenario: List && create && edit  organization as Re-seller

    Given I mock the login session as "reseller"
    Then I should be on "/"
    And I go to "/organizations"
    Then I should see "You don't have access to this page , please contact the admin !"
    And I go to "/organizations/myorganizations"
    Then I should see "ORGANIZATIONS"
    And I go to "/organizations/type"
    Then I should see "Organization Type"
    And I go to "/organizations/atcs"
    Then I should see "ATCS"
    And I go to "/organizations/atps"
    Then I should see "ATPS"
    And I go to "/organizations/distributors"
    Then I should see "DISTRIBUTORS"
    And I go to "/organizations/resellers"
    Then I should see "RE-SELLERS"

    #all the following organizations with id 1,2,3,4 are ATC , ATP , Distributor , Re-seller 
    And I go to "/organizations/new/1"
    Then I should see "You need to be Test Center Administrator, And you need to accept the corresponding Agreement statement"
    And I go to "/organizations/new/2"
    Then I should see "You need to be Training Manager, And you need to accept the corresponding Agreement statement"
    And I go to "/organizations/new/3"
    Then I should see "You need to be Distributor, And you need to accept the corresponding Agreement statement"
    And I go to "/organizations/new/4"
    Then I should see "CREATE AN ORGANIZATION"


Scenario: List && create && edit  organization as instructor

    Given I mock the login session as "instructor"
    Then I should be on "/"
    And I go to "/organizations"
    Then I should see "You don't have access to this page , please contact the admin !"
    And I go to "/organizations/myorganizations"
    Then I should see "You don't have access to this page , please contact the admin !"
    And I go to "/organizations/type"
    Then I should see "You don't have access to this page , please contact the admin !"
    And I go to "/organizations/atcs"
    Then I should see "You don't have access to this page , please contact the admin !"
    And I go to "/organizations/atps"
    Then I should see "You don't have access to this page , please contact the admin !"
    And I go to "/organizations/distributors"
    Then I should see "You don't have access to this page , please contact the admin !"
    And I go to "/organizations/resellers"
    Then I should see "You don't have access to this page , please contact the admin !"

    #all the following organizations with id 1,2,3,4 are ATC , ATP , Distributor , Re-seller 
    And I go to "/organizations/new/1"
    Then I should see "You don't have access to this page , please contact the admin !"
    And I go to "/organizations/new/2"
    Then I should see "You don't have access to this page , please contact the admin !"
    And I go to "/organizations/new/3"
    Then I should see "You don't have access to this page , please contact the admin !"
    And I go to "/organizations/new/4"
    Then I should see "You don't have access to this page , please contact the admin !"
    
