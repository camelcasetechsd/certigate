Feature: Organization Validation
    Testing admin, training manager and testing center administrator available operations

Scenario: admin select organization type without required fields
    Given I mock the login session as "admin"
    And I am on "/organizations/type"
    Then I press "Start!"
    And I should see "Value is required and can't be empty"

@javascript
Scenario: admin create organization without required fields
    Given I mock the login session
    And I am on "/organizations/new/1/2/3/4"
    And I uncheck "status"
    Then I press "Create"
    And I should be on "/organizations/new/1/2/3/4"
    And I should see "Value is required and can't be empty" 34 times
    And I should see "File was not uploaded" 5 times
    And I should see "You must agree to the terms of use" 2 times
    And I should see "Latitude is required"
    And I should see "Longitude is required"



@javascript
Scenario: checking uniqueness of "Commercial Name" 
    Given I mock the login session as "tmuser"
    And I go to "/organizations/new/2"
    Then I should see atp fields
    Then I should not see atc fields
    And I fill in "commercialName" with "atpDummy"
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
    Then I should see "Sorry, This commercial name already exists !"