Feature: Organizations
    Testing admin, training manager and testing center administrator available operations

Scenario: admin select organization type without required fields
    Given I mock the login session
        And I am on "/organizations/type"
    Then I press "Start!"
        And I should see "Value is required and can't be empty"

@javascript
Scenario: admin create organization without required fields
    Given I mock the login session
        And I am on "/organizations/new/1/2/3/4"
        And I fill in "commercialName" with ""
        And I fill in "commercialNameAr" with ""
        And I fill in "ownerName" with ""
        And I fill in "ownerNameAr" with ""
        And I fill in "ownerNameAr" with ""
        And I select "" from "region[]"
        And I select "" from "governorate[]"
        And I fill in "ownerNationalId" with ""
        And I fill in "CRNo" with ""
        And I fill in "CRExpirationHj" with ""
        And I fill in "CRExpiration" with ""
        And I fill in "phone1" with ""
        And I fill in "phone2" with ""
        And I fill in "phone3" with ""
        And I fill in "fax" with ""
        And I fill in "addressLine1" with ""
        And I fill in "addressLine2" with ""
        And I fill in "addressLine1Ar" with ""
        And I fill in "addressLine2Ar" with ""
        And I fill in "city" with ""
        And I fill in "cityAr" with ""
        And I fill in "zipCode" with ""
        And I fill in "website" with ""
        And I fill in "email" with ""
        And I select "-- Select --" from "focalContactPerson_id"
        And I uncheck "status"
        And I attach the file "user.png" to "CRAttachment"
    Then I press "Create"
        And I should be on "/organizations/new/1/2/3/4"
        And I should see "Value is required and can't be empty" 20 times
        And I should see "Latitude is required"
        And I should see "Longitude is required"
