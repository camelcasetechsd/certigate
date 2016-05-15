Feature: distributor Crud
### crud Tests

@javascript
Scenario: create organization Distributor as Distributor user

    Given I mock the login session as "distributor"
    And I go to "/organizations/new/3"
    Then I should not see atc fields
    Then I should not see atp fields
    And I fill in "commercialName" with "Distributor test 1"
    And I fill in "commercialNameAr" with "Distributor test 1 arabic"
    And I fill in "ownerName" with "Distributor owner 1"
    And I fill in "ownerNameAr" with "Distributor owner 1 in arabic"
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
    And I select the optgroup with 2 option value from "focalContactPerson_id"
    And I press "Create"
    Then I should be on "/organizations/myorganizations"
    Then I should find organization with name "Distributor test 1" type "Distributor" and expiration "NO Expiration Date"
    
    #Making sure that data is saved right
    When I perform "More" action on row with "Distributor test 1" value
    Then I should see "Distributor test 1"
    Then I should see "Distributor test 1 arabic"
    Then I should see "Distributor owner 1"
    Then I should see "Distributor owner 1 in arabic"
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
    Then I should see "xyz Street xyz Region"
    Then I should see "XYZ Street XYZ Region"
    Then I should see "Alexandria"
    

Scenario: Testing Managing user List 

    Given I mock the login session as "distributor"
    And I go to "/organizations/myorganizations"
    Then row with value "Distributor test 1" should not contain "Manage Users" action
    Then row with value "Distributor test 1" should not contain "Renewal" action


Scenario: Testing Oragnization Edit

    Given I mock the login session as "distributor"
    And I go to "/organizations/myorganizations"
    When I perform "Edit" action on row with "Distributor test 1" value
    Then I should see "EDIT ORGANIZATION"
    Then I should not see atc renewal fields
    Then I should not see atp renewal fields
    And I fill in "commercialName" with "Edited Distributor organization"
    And I press "Submit for admin approval"
    #wating for admin approval
    Then I should be on "/organizations/myorganizations"
    Then I should see "Distributor test 1"
    Then I should not see "Edited Distributor organization"
    
    
    

    
    