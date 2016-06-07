Feature: tmuser Crud
### crud Tests

@javascript
Scenario: create organization ATP as TM

    Given I mock the login session as "tmuser"
    And I go to "/organizations/new/2"
    Then I should see atp fields
    Then I should not see atc fields
    And I fill in "commercialName" with "Atp test 1"
    And I fill in "commercialNameAr" with "Atp test 1 arabic"
    And I fill in "ownerName" with "atp owner 1"
    And I fill in "ownerNameAr" with "atp owner 1 in arabic"
    And I select "Northern Borders" from "region[]"
    And I select "gov1" from "governorate[]"
    And I fill in "mapSearch" with "cairo"
    And I press "Search"
    And I fill in "ownerNationalId" with "1548752617646"
    And I fill in "CRNo" with "1564874653188"
    And I fill in "CRExpirationHj" with "28/01/1441"
    And I fill in "CRExpiration" with "27/09/2019"
    And I attach the file "user.png" to "CRAttachment"
    And I fill in "phone1" with "555-555-5555"
    And I fill in "phone2" with "666-666-6666"
    And I fill in "phone3" with "777-777-7777"
    And I fill in "fax" with "XXXX"
    And I fill in "addressLine1" with "ABC Street ABC Region"
    And I fill in "addressLine2" with "XYZ Street XYZ Region"
    And I fill in "addressLine1Ar" with "abc Street abc Region"
    And I fill in "addressLine2Ar" with "xyz Street xyz Region"
    And I fill in "city" with "Alexandria"
    And I fill in "cityAr" with "Alexandria"
    And I fill in "zipCode" with "99999"
    And I fill in "website" with "www.google.com"
    And I fill in "email" with "camelCase@camelCase.com"
    And I fill in "atpLicenseNo" with "156468787687"
    And I fill in "atpLicenseExpirationHj" with "28/01/1441"
    And I fill in "atpLicenseExpiration" with "27/09/2019"
    And I attach the file "user.png" to "atpLicenseAttachment"
    And I attach the file "user.png" to "atpWireTransferAttachment"
    And I fill in "classesNo" with "20"
    And I fill in "pcsNo_class" with "15"
    And I select the optgroup with 3 option value from "trainingManager_id"
    And I select the optgroup with 1 option value from "focalContactPerson_id"
    And I check "atpPrivacyStatement"
    And I press "Create"
    Then I should be on "/organizations/myorganizations"
    Then I should find organization with name "Atp test 1" type "ATP" and expiration "27/09/2019"
    
    #Making sure that data is saved right
    When I perform "More" action on row with "Atp test 1" value
    Then I should see "Atp test 1"
    Then I should see "Atp test 1 arabic"
    Then I should see "atp owner 1"
    Then I should see "atp owner 1 in arabic"
    Then I should see "1548752617646"
    Then I should see "1564874653188"
    Then I should see "2019-09-27"
    Then I should see "555-555-5555"
    Then I should see "666-666-6666"
    Then I should see "777-777-7777"
    Then I should see "XXXX"
    Then I should see "camelCase@camelCase.com"
    Then I should see "ABC Street ABC Region"
    Then I should see "abc Street abc Region"
    Then I should see "XYZ Street XYZ Region"
    Then I should see "xyz Street xyz Region"
    Then I should see "Alexandria"
    Then I should see "99999"
    Then I should see "156468787687"
    Then I should see "2019-09-27"
    Then I should see "15"
    Then I should see "20"

    

Scenario: Testing Managing user List 

    Given I mock the login session as "tmuser"
    And I go to "/organizations/myorganizations"
    When I perform "Manage Users" action on row with "Atp test 1" value
    # selected tmuser as training manager (checking if there'll be 1 org user or not)
    Then I should see only 1 row


Scenario: Admin approval

    Given I mock the login session as "admin"
    And I go to "/organizations/atps"
    Then I should not see "Atp test 1"
    And I go to "/organizations"
    When I perform "View" action on row with "atp owner 1" value
    And I press "Approve all changes"
    Then I should be on "/organizations"
    And I go to "/organizations/atps"
    Then I should see "Atp test 1"




Scenario: Testing Organization Edit

    Given I mock the login session as "tmuser"
    And I go to "/organizations/myorganizations"
    When I perform "Edit" action on row with "Atp test 1" value
    Then I should see "EDIT ORGANIZATION"
    Then I should not see atc renewal fields
    And I fill in "commercialName" with "Edited organization"
    And I fill in "classesNo" with "770"
    And I fill in "pcsNo_class" with "858"
    And I check "atpPrivacyStatement"
    And I press "Submit for admin approval"
    #waiting for admin approval
    Then I should be on "/organizations/myorganizations"
    Then I should see "Atp test 1"
    Then I should not see "Edited organization"
    
    
    

    
    