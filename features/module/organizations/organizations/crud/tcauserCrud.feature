Feature: tcauser Crud
### crud Tests

@javascript
Scenario: create organization ATC as TCA

    Given I mock the login session as "tcauser"
    And I go to "/organizations/new/1"
    Then I should see atc fields
    Then I should not see atp fields
    And I fill in "commercialName" with "Atc test 1"
    And I fill in "commercialNameAr" with "Atc test 1 arabic"
    And I fill in "ownerName" with "atc owner 1"
    And I fill in "ownerNameAr" with "atc owner 1 in arabic"
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
    And I fill in "atcLicenseNo" with "156468787687"
    And I fill in "atcLicenseExpirationHj" with "28/01/1441"
    And I fill in "atcLicenseExpiration" with "27/09/2019"
    And I attach the file "user.png" to "atcLicenseAttachment"
    And I attach the file "user.png" to "atcWireTransferAttachment"
    And I fill in "labsNo" with "15"
    And I fill in "pcsNo_lab" with "20"
    And I fill in "internetSpeed_lab" with "2048"
    And I select "Microsoft Windows XP" from "operatingSystem"
    And I select "Arabic" from "operatingSystemLang"
    And I select "Office 2016" from "officeVersion"
    And I select "Arabic" from "officeLang"
    And I select the optgroup with 1 option value from "testCenterAdmin_id"
    And I select the optgroup with 2 option value from "focalContactPerson_id"
    And I check "atcPrivacyStatement"
    And I press "Create"
    Then I should be on "/organizations/myorganizations"
    Then I should find organization with name "Atc test 1" type "ATC" and expiration "27/09/2019"
    
    #Making sure that data is saved right
    When I perform "More" action on row with "Atc test 1" value
    Then I should see "Atc test 1"
    Then I should see "Atc test 1 arabic"
    Then I should see "atc owner 1"
    Then I should see "atc owner 1 in arabic"
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
    Then I should see "Microsoft Windows XP"
    Then I should see "Arabic"
    Then I should see "Office 2016"
    

Scenario: Testing Managing user List 

    Given I mock the login session as "tcauser"
    And I go to "/organizations/myorganizations"
    When I perform "Manage Users" action on row with "Atc test 1" value
    # 2 rows as TCAs for the creator and the other TCA
    Then I should see only 2 row


Scenario: Admin approval

    Given I mock the login session as "admin"
    And I go to "/organizations/atcs"
    Then I should not see "Atc test 1"
    And I go to "/organizations"
    When I perform "View" action on row with "atc owner 1" value
    And I press "Approve all changes"
    Then I should be on "/organizations"
    And I go to "/organizations/atcs"
    Then I should see "Atc test 1"


Scenario: Testing Organization Edit

    Given I mock the login session as "tcauser"
    And I go to "/organizations/myorganizations"
    When I perform "Edit" action on row with "Atc test 1" value
    Then I should see "EDIT ORGANIZATION"
    Then I should not see atc renewal fields
    And I fill in "commercialName" with "Edited atc organization"
    And I fill in "pcsNo_lab" with "50"
    And I fill in "internetSpeed_lab" with "1024"
    And I select "Red Hat Enterprise Linux 7" from "operatingSystem"
    And I select "English" from "operatingSystemLang"
    And I select "Office 2013" from "officeVersion"
    And I check "atcPrivacyStatement"
    And I press "Submit for admin approval"
    #waiting for admin approval
    Then I should be on "/organizations/myorganizations"
    Then I should see "Atc test 1"
    Then I should not see "Edited atc organization"
    
    
    

    
    