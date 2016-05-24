Feature: renewal

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
    And I fill in "atpLicenseExpirationHj" with "29/08/1419"
    And I fill in "atpLicenseExpiration" with "18/12/1998"
    And I attach the file "user.png" to "atpLicenseAttachment"
    And I attach the file "user.png" to "atpWireTransferAttachment"
    And I fill in "classesNo" with "20"
    And I fill in "pcsNo_class" with "15"
    And I select the optgroup with 3 option value from "trainingManager_id"
    And I select the optgroup with 1 option value from "focalContactPerson_id"
    And I check "atpPrivacyStatement"
    And I press "Create"
    Then I should be on "/organizations/myorganizations"
    Then I should find organization with name "Atp test 1" type "ATP" and expiration "18/12/1998"
    Then I should see organization with "Atp test 1" commercial name need to be renewed

    
Scenario: renew an organization

    Given I mock the login session as "tmuser"
    And I go to "/organizations/myorganizations"
    Then I should see organization with "Atp test 1" commercial name need to be renewed
    When I perform "Renewal" action on row with "Atp test 1" value
    Then I should see atp renewal fields
    Then the "atpLicenseNo" field should contain "156468787687"
    Then the "atpLicenseExpirationHj" field should contain "29/08/1419"
    Then the "atpLicenseExpiration" field should contain "18/12/1998"
    And I fill in "atpLicenseNo" with "0123456789"
    And I fill in "atpLicenseExpirationHj" with "08/08/1445"
    And I fill in "atpLicenseExpiration" with "18/02/2024"
    And I attach the file "user.png" to "atpLicenseAttachment"
    And I attach the file "user.png" to "atpWireTransferAttachment"
    And I press "Renew"
    Then I should be on "/organizations/myorganizations"
    #we show in my organizations the edited data 
    Then I should find organization with name "Atp test 1" type "ATP" and expiration "18/02/2024"
    #we show in other list pages last approved data by admin
    And I go to "/organizations/atps"
    Then I should find organization with name "Atp test 1" type "ATP" and expiration "18/12/1998"


Scenario: Approving renewal by admin

    Given I mock the login session as "admin"
    And I go to "/organizations/atps"
    Then I should find organization with name "Atp test 1" type "ATP" and expiration "18/12/1998"
    When I perform "View" action on row with "Atp test 1" value
    And I press "Approve all changes"
    

Scenario: reviewing changes approved by admin

    Given I mock the login session as "tmuser"
    And I go to "/organizations/myorganizations"
    Then I should find organization with name "Atp test 1" type "ATP" and expiration "18/02/2024"
    And I perform "More" action on row with "Atp test 1" value
    And I should see "Atp test 1"
    And I should see "Atp test 1 arabic"
    And I should see "atp owner 1"
    And I should see "atp owner 1 in arabic"
    And I should see "1548752617646"
    And I should see "1564874653188"
